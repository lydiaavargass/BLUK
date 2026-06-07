<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Muestra la página de inicio con los productos nuevos y más vendidos.
     */
    public function index(): View
    {
        $loNuevo = Product::with('category')
            ->where('is_active', true)
            ->latest()
            ->take(4)
            ->get();

        $masVendidos = Product::with('category')
            ->where('is_active', true)
            ->orderBy('stock', 'desc')
            ->take(4)
            ->get();

        return view('welcome', compact('loNuevo', 'masVendidos'));
    }
}
