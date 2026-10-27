<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Order;
use App\Models\Product;
use App\Models\Recipe;
use App\Services\RecipeImporter;

class DashboardController extends Controller
{
    public function index(RecipeImporter $importer)
    {
        return view('admin.dashboard', [
            'recipeTotal' => Recipe::count(),
            'recipePublished' => Recipe::where('is_published', true)->count(),
            'productTotal' => Product::count(),
            'productActive' => Product::where('is_active', true)->count(),
            'orderTotal' => Order::count(),
            'orderNew' => Order::where('status', 'baru')->count(),
            'messageTotal' => Message::count(),
            'pendingImport' => $importer->pending(),
            'latestOrders' => Order::latest()->limit(5)->get(),
        ]);
    }
}
