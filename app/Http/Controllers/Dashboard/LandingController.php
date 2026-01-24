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
        $offers = Offer::with(['images', 'category'])
            ->where('is_active', true)
            // ->latest()
            ->take(8)
            ->get();

        $categories = Category::latest()->take(8)->get();

        $sliders = Slider::where('status', true)->latest()->take(5)->get();

        return view('landing.index', compact('offers', 'categories', 'sliders'));
    }
}
