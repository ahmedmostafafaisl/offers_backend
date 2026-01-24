<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Offer;
use App\Models\Category;
use App\Models\OfferImage;
use App\Models\OfferSocialMedia;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\Dashboard\OffersRepositoryInterface;
use App\Http\Requests\Dashboard\Offers\StoreOfferRequest;
use App\Http\Requests\Dashboard\Offers\UpdateOfferRequest;

class OffersController extends Controller
{
    public function __construct(private OffersRepositoryInterface $repo) {}

    public function index()
    {
        $rows = $this->repo->paginate([
            'search' => request('search'),
            'category_id' => request('category_id'),
            'user_id' => request('user_id'),
            'is_active' => request('is_active'),
            'city' => request('city'),
        ], (int) request('per_page', 10));

        // for filters selects
        $categories = Category::orderBy('name')->get();
        $providers = User::where('type', 'provider')->orderBy('id')->get();

        return view('dashboard.offers.index', compact('rows', 'categories', 'providers'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $providers = User::where('type', 'provider')->orderBy('id')->get();

        return view('dashboard.offers.create', compact('categories', 'providers'));
    }

    public function store(StoreOfferRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = (bool)($data['is_active'] ?? true);

        DB::transaction(function () use ($request, $data, &$offer) {

            /** @var Offer $offer */
            $offer = Offer::create($data);
            event(new \App\Events\OfferCreated($offer));

            // images upload
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $path = $file->store('offers', 'public');
                    OfferImage::create(['offer_id' => $offer->id, 'image' => $path]);
                }
            }

            // social media
            if (!empty($data['social_media'])) {
                foreach ($data['social_media'] as $s) {
                    OfferSocialMedia::create([
                        'offer_id' => $offer->id,
                        'platform' => $s['platform'],
                        'url' => $s['url'],
                    ]);
                }
            }
        });

        return redirect()->route('dashboard.offers.index')->with('success', 'Offer created');
    }

    public function edit($offer)
    {
        $row = Offer::with(['images', 'socialMedia'])->findOrFail((int)$offer);
        $categories = Category::orderBy('name')->get();
        $providers = User::where('type', 'provider')->orderBy('id')->get();

        return view('dashboard.offers.edit', compact('row', 'categories', 'providers'));
    }

    public function update(UpdateOfferRequest $request, $offer)
    {
        $data = $request->validated();
        $data['is_active'] = (bool)($data['is_active'] ?? false);

        DB::transaction(function () use ($request, $data, $offer) {
            $row = Offer::with(['images', 'socialMedia'])->findOrFail((int)$offer);
            $row->update($data);

            // delete images
            if (!empty($data['delete_image_ids'])) {
                $imgs = OfferImage::whereIn('id', $data['delete_image_ids'])
                    ->where('offer_id', $row->id)->get();

                foreach ($imgs as $img) {
                    Storage::disk('public')->delete($img->image);
                    $img->delete();
                }
            }

            // add new images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $path = $file->store('offers', 'public');
                    OfferImage::create(['offer_id' => $row->id, 'image' => $path]);
                }
            }

            // replace socials (simple + clean)
            OfferSocialMedia::where('offer_id', $row->id)->delete();
            if (!empty($data['social_media'])) {
                foreach ($data['social_media'] as $s) {
                    OfferSocialMedia::create([
                        'offer_id' => $row->id,
                        'platform' => $s['platform'],
                        'url' => $s['url'],
                    ]);
                }
            }
        });

        return redirect()->route('dashboard.offers.index')->with('success', 'Offer updated');
    }

    public function destroy($offer)
    {
        $row = Offer::with(['images', 'socialMedia'])->findOrFail((int)$offer);

        DB::transaction(function () use ($row) {
            foreach ($row->images as $img) {
                Storage::disk('public')->delete($img->image);
                $img->delete();
            }
            $row->socialMedia()->delete();
            $row->delete();
        });

        return back()->with('success', 'Offer deleted');
    }
}
