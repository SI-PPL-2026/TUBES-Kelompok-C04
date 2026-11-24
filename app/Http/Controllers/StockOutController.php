<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\StockOut;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StockOutController extends Controller
{
    public function index(): View
    {
        $stockOuts = StockOut::with(['ingredient', 'user'])
            ->latest('issued_date')
            ->latest('id')
            ->get();

        return view('stock-outs.index', compact('stockOuts'));
    }

    public function create(): View
    {
        $ingredients = Ingredient::orderBy('name')->get();

        return view('stock-outs.create', compact('ingredients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ingredient_id' => ['required', 'exists:ingredients,id'],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'issued_date' => ['required', 'date'],
            'outlet_name' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated) {
            $ingredient = Ingredient::lockForUpdate()->findOrFail($validated['ingredient_id']);
            $requestedQty = (float) $validated['quantity'];
            $availableStock = (float) $ingredient->current_stock;

            if ($requestedQty > $availableStock) {
                throw ValidationException::withMessages([
                    'quantity' => 'Stok tidak mencukupi. Stok tersedia: ' . number_format($availableStock, 2, ',', '.') . " {$ingredient->unit}.",
                ]);
            }

            StockOut::create([
                ...$validated,
                'user_id' => auth()->id(),
            ]);

            $ingredient->decrement('current_stock', $requestedQty);
        });

        return redirect()
            ->route('stock-outs.index')
            ->with('status', 'Bahan keluar berhasil dicatat dan stok bahan baku telah dikurangi.');
    }
}