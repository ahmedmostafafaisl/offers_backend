<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Offer;
use Illuminate\Http\Request;

/**
 * Mark offers / categories as featured on the website landing page.
 */
class FeaturedController extends Controller
{
    public function offer(Request $request, int $offer)
    {
        $this->apply(Offer::query()->whereKey($offer), $request);

        return back()->with('success', __('site.featured_saved'));
    }

    public function category(Request $request, int $category)
    {
        $this->apply(Category::query()->whereKey($category), $request);

        return back()->with('success', __('site.featured_saved'));
    }

    private function apply($query, Request $request): void
    {
        $data = $request->validate([
            'is_featured' => ['required', 'boolean'],
            'featured_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);

        // update() on the query builder, so $fillable of the models is not involved
        abort_unless($query->exists(), 404);

        $query->update([
            'is_featured' => (bool) $data['is_featured'],
            'featured_order' => (int) ($data['featured_order'] ?? 0),
        ]);
    }
}
