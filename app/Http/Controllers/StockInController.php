<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\StockIn;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockInController extends Controller
{
    public function index(): View
    {
        $stockIns = StockIn::with(['ingredient', 'supplier', 'user'])
            ->latest('received_date')
            ->latest('id')
            ->get();

        return view('stock-ins.index', compact('stockIns'));
    }

    public function create(): View
    {
        $ingredients = Ingredient::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('stock-ins.create', compact('ingredients', 'suppliers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ingredient_id' => ['required', 'exists:ingredients,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'received_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated) {
            StockIn::create([
                ...$validated,
                'user_id' => auth()->id(),
            ]);

            $ingredient = Ingredient::lockForUpdate()->findOrFail($validated['ingredient_id']);
            $ingredient->increment('current_stock', (float) $validated['quantity']);
        });

        return redirect()
            ->route('stock-ins.index')
            ->with('status', 'Bahan masuk berhasil dicatat dan stok bahan baku telah ditambahkan.');
    }
}