<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\Dashboard\OfferSocialMediaRepositoryInterface;

class OfferSocialMediaController extends Controller
{
    public function __construct(private OfferSocialMediaRepositoryInterface $repo) {}

    public function index()
    {
        $rows = $this->repo->paginate([
            'search' => request('search'),
            'offer_id' => request('offer_id'),
            'platform' => request('platform'),
        ], (int) request('per_page', 10));

        $offers = Offer::orderBy('id', 'desc')->limit(200)->get();

        return view('dashboard.offer_social.index', compact('rows', 'offers'));
    }

    public function create()
    {
        $offers = Offer::orderBy('id', 'desc')->limit(200)->get();
        return view('dashboard.offer_social.create', compact('offers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'offer_id' => 'required|exists:offers,id',
            'platform' => 'required|string|max:50',
            'url' => 'required|url|max:255',
        ]);

        $this->repo->create($data);

        return redirect()->route('dashboard.offer-social-media.index')->with('success', 'Social media created');
    }

    public function edit($offer_social_medium)
    {
        $row = $this->repo->findOrFail((int)$offer_social_medium);
        $offers = Offer::orderBy('id', 'desc')->limit(200)->get();

        return view('dashboard.offer_social.edit', compact('row', 'offers'));
    }

    public function update(Request $request, $offer_social_medium)
    {
        $data = $request->validate([
            'offer_id' => 'required|exists:offers,id',
            'platform' => 'required|string|max:50',
            'url' => 'required|url|max:255',
        ]);

        $this->repo->update((int)$offer_social_medium, $data);

        return redirect()->route('dashboard.offer-social-media.index')->with('success', 'Social media updated');
    }

    public function destroy($offer_social_medium)
    {
        $this->repo->delete((int)$offer_social_medium);

        return back()->with('success', 'Social media deleted');
    }
}
