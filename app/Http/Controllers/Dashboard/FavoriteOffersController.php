<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Offer;
use App\Http\Controllers\Controller;
use App\Interfaces\Dashboard\FavoriteOffersRepositoryInterface;
use App\Http\Requests\Dashboard\FavoriteOffers\StoreFavoriteOfferRequest;
use App\Http\Requests\Dashboard\FavoriteOffers\UpdateFavoriteOfferRequest;

class FavoriteOffersController extends Controller
{
    public function __construct(private FavoriteOffersRepositoryInterface $repo) {}

    public function index()
    {
        $rows = $this->repo->paginate([
            'search' => request('search'),
            'user_id' => request('user_id'),
            'offer_id' => request('offer_id'),
        ], (int) request('per_page', 10));

        $users = User::orderBy('id', 'desc')->limit(300)->get();
        $offers = Offer::orderBy('id', 'desc')->limit(300)->get();

        return view('dashboard.favorite_offers.index', compact('rows', 'users', 'offers'));
    }

    public function create()
    {
        $users = User::orderBy('id', 'desc')->limit(300)->get();
        $offers = Offer::orderBy('id', 'desc')->limit(300)->get();
        return view('dashboard.favorite_offers.create', compact('users', 'offers'));
    }

    public function store(StoreFavoriteOfferRequest $request)
    {
        $this->repo->create($request->validated());
        return redirect()->route('dashboard.favorite-offers.index')->with('success', 'Favorite created');
    }

    public function edit($favorite_offer)
    {
        $row = $this->repo->findOrFail((int)$favorite_offer);
        $users = User::orderBy('id', 'desc')->limit(300)->get();
        $offers = Offer::orderBy('id', 'desc')->limit(300)->get();
        return view('dashboard.favorite_offers.edit', compact('row', 'users', 'offers'));
    }

    public function update(UpdateFavoriteOfferRequest $request, $favorite_offer)
    {
        $this->repo->update((int)$favorite_offer, $request->validated());
        return redirect()->route('dashboard.favorite-offers.index')->with('success', 'Favorite updated');
    }

    public function destroy($favorite_offer)
    {
        $this->repo->delete((int)$favorite_offer);
        return back()->with('success', 'Favorite deleted');
    }
}
