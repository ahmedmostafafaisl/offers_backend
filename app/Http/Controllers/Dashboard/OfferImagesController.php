<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Offer;
use App\Models\OfferImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\Dashboard\OfferImagesRepositoryInterface;

class OfferImagesController extends Controller
{
    public function __construct(private OfferImagesRepositoryInterface $repo) {}

    public function index()
    {
        $rows = $this->repo->paginate([
            'search' => request('search'),
            'offer_id' => request('offer_id'),
        ], (int) request('per_page', 10));

        $offers = Offer::orderBy('id', 'desc')->limit(200)->get();
        return view('dashboard.offer_images.index', compact('rows', 'offers'));
    }

    public function create()
    {
        $offers = Offer::orderBy('id', 'desc')->limit(200)->get();
        return view('dashboard.offer_images.create', compact('offers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'offer_id' => 'required|exists:offers,id',
            'image_file' => 'required|image|mimes:jpg,jpeg,png,webp|max:8048',
        ]);

        $path = $request->file('image_file')->store('offers', 'public');

        $this->repo->create([
            'offer_id' => $data['offer_id'],
            'image' => $path,
        ]);

        return redirect()->route('dashboard.offer-images.index')->with('success', 'Offer image created');
    }

    public function edit($offer_image)
    {
        $row = $this->repo->findOrFail((int)$offer_image);
        $offers = Offer::orderBy('id', 'desc')->limit(200)->get();
        return view('dashboard.offer_images.edit', compact('row', 'offers'));
    }

    public function update(Request $request, $offer_image)
    {
        $row = $this->repo->findOrFail((int)$offer_image);

        $data = $request->validate([
            'offer_id' => 'required|exists:offers,id',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8048',
        ]);

        $update = ['offer_id' => $data['offer_id']];

        if ($request->hasFile('image_file')) {
            if ($row->image) Storage::disk('public')->delete($row->image);
            $update['image'] = $request->file('image_file')->store('offers', 'public');
        }

        $this->repo->update((int)$offer_image, $update);

        return redirect()->route('dashboard.offer-images.index')->with('success', 'Offer image updated');
    }

    public function destroy($offer_image)
    {
        $row = $this->repo->findOrFail((int)$offer_image);
        if ($row->image) Storage::disk('public')->delete($row->image);

        $this->repo->delete((int)$offer_image);

        return back()->with('success', 'Offer image deleted');
    }
}
