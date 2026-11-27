<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Supplier;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'ingredientCount' => Ingredient::count(),
            'supplierCount' => Supplier::count(),
            'lowStockCount' => Ingredient::lowStock()->count(),
            'recentIngredients' => Ingredient::latest()->limit(5)->get(),
            'recentSuppliers' => Supplier::latest()->limit(4)->get(),
        ]);
    }
}
