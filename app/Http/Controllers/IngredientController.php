<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IngredientController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $ingredients = Ingredient::query()
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            }))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('ingredients.index', compact('ingredients', 'search'));
    }

    public function create(): View
    {
        return view('ingredients.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Ingredient::create($this->validatedData($request));

        return redirect()->route('ingredients.index')->with('status', 'Bahan baku berhasil ditambahkan.');
    }

    public function edit(Ingredient $ingredient): View
    {
        return view('ingredients.edit', compact('ingredient'));
    }

    public function update(Request $request, Ingredient $ingredient): RedirectResponse
    {
        $ingredient->update($this->validatedData($request, $ingredient));

        return redirect()->route('ingredients.index')->with('status', 'Data bahan baku berhasil diperbarui.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?Ingredient $ingredient = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:40', 'alpha_dash', 'unique:ingredients,code,'.$ingredient?->id],
            'name' => ['required', 'string', 'max:160'],
            'category' => ['required', 'string', 'max:100'],
            'unit' => ['required', 'string', 'max:30'],
            'minimum_stock' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
