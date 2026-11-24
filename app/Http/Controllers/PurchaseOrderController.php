<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PurchaseOrderController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $status = trim((string) $request->query('status', ''));

        $orders = PurchaseOrder::query()
            ->with(['supplier', 'user'])
            ->withCount('items')
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('order_number', 'like', "%{$search}%")
                        ->orWhereHas('supplier', fn ($supplierQuery) => $supplierQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest('order_date')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('purchase-orders.index', compact('orders', 'search', 'status'));
    }

    public function create(): View
    {
        $suppliers = Supplier::orderBy('name')->get();
        $ingredients = Ingredient::orderBy('name')->get();
        $nextOrderNumber = $this->generateOrderNumber();

        return view('purchase-orders.create', compact('suppliers', 'ingredients', 'nextOrderNumber'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order_number' => ['required', 'string', 'max:50', 'unique:purchase_orders,order_number'],
            'supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
            'order_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.ingredient_id' => ['required', 'integer', 'exists:ingredients,id', 'distinct'],
            'items.*.quantity' => ['required', 'numeric', 'gt:0', 'max:9999999999.99'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $order = PurchaseOrder::create([
                'order_number' => $validated['order_number'],
                'supplier_id' => $validated['supplier_id'],
                'user_id' => $request->user()?->id,
                'order_date' => $validated['order_date'],
                'status' => PurchaseOrder::STATUS_DIPESAN,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                $order->items()->create([
                    'ingredient_id' => $item['ingredient_id'],
                    'quantity' => $item['quantity'],
                ]);
            }
        });

        return redirect()
            ->route('purchase-orders.index')
            ->with('status', 'Pesanan ke supplier berhasil dicatat.');
    }

    public function show(PurchaseOrder $purchaseOrder): View
    {
        $purchaseOrder->load(['supplier', 'user', 'items.ingredient']);

        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    private function generateOrderNumber(): string
    {
        $prefix = 'PO-' . now()->format('Ymd') . '-';
        $lastOrder = PurchaseOrder::where('order_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->first();

        $nextNumber = 1;
        if ($lastOrder) {
            $suffix = (int) substr($lastOrder->order_number, strlen($prefix));
            $nextNumber = $suffix + 1;
        }

        return $prefix . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }
}