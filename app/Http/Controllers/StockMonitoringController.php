<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockMonitoringController extends Controller
{
    /**
     * PBI 9: Read Stok Bahan (Daftar Stok & Ketersediaan Gudang)
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $category = trim((string) $request->query('category', ''));
        $status = trim((string) $request->query('status', ''));

        $ingredients = Ingredient::query()
            ->withSum('stockIns', 'quantity')
            ->withSum('stockOuts', 'quantity')
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            }))
            ->when($category !== '', fn ($query) => $query->where('category', $category))
            ->when($status === 'safe', fn ($query) => $query->safeStock())
            ->when($status === 'low', fn ($query) => $query->lowStock())
            ->when($status === 'out', fn ($query) => $query->outOfStock())
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $categories = Ingredient::query()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $totalIngredients = Ingredient::count();
        $safeStockCount = Ingredient::safeStock()->count();
        $lowStockCount = Ingredient::lowStock()->count();
        $outOfStockCount = Ingredient::outOfStock()->count();

        return view('stocks.index', compact(
            'ingredients',
            'categories',
            'search',
            'category',
            'status',
            'totalIngredients',
            'safeStockCount',
            'lowStockCount',
            'outOfStockCount',
        ));
    }

    /**
     * PBI 10: Read Stok Minimum (Bahan di Bawah Batas Minimum)
     */
    public function minimum(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $category = trim((string) $request->query('category', ''));

        $ingredients = Ingredient::query()
            ->lowStock()
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            }))
            ->when($category !== '', fn ($query) => $query->where('category', $category))
            ->orderByRaw('(minimum_stock - current_stock) DESC')
            ->orderBy('current_stock')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $categories = Ingredient::query()
            ->lowStock()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $totalLowStock = Ingredient::lowStock()->count();
        $outOfStockCount = Ingredient::lowStock()->outOfStock()->count();

        return view('stocks.minimum', compact(
            'ingredients',
            'categories',
            'search',
            'category',
            'totalLowStock',
            'outOfStockCount',
        ));
    }
}
