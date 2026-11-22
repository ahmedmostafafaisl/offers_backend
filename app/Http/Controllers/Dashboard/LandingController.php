<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Offer;
use App\Models\Slider;
use App\Models\Category;
use App\Http\Controllers\Controller;

class LandingController extends Controller
{
    public function index()
    {
        // Featured offers (chosen by the admin). Nothing featured => the same list as before.
        $offers = Offer::with(['images', 'category'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('featured_order')
            ->orderByDesc('id')
            ->take(12)
            ->get();

        if ($offers->isEmpty()) {
            $offers = Offer::with(['images', 'category'])
                ->where('is_active', true)
                // ->latest()
                ->take(8)
                ->get();
        }

        // Featured categories (chosen by the admin). Nothing featured => latest 8 as before.
        $categories = Category::where('is_featured', true)
            ->orderBy('featured_order')
            ->orderByDesc('id')
            ->take(12)
            ->get();

        if ($categories->isEmpty()) {
            $categories = Category::latest()->take(8)->get();
        }

        $sliders = Slider::where('status', true)->latest()->take(5)->get();

        return view('landing.index', compact('offers', 'categories', 'sliders'));
    }
}
