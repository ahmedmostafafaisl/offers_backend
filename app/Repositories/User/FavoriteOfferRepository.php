<?php

namespace App\Repositories\User;

use App\Models\FavoriteOffer;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\User\FavoriteOfferInterface;

class FavoriteOfferRepository implements FavoriteOfferInterface
{
    public function index()
    {
        $user = Auth::user();
        return $user->favoriteOffers()->with('category', 'images')->get();
    }

    public function store(array $data)
    {
        $user = Auth::user();

        if ($user->favoriteOffers()->where('offer_id', $data['offer_id'])->exists()) {
            return ['message' => 'Offer already in favorites'];
        }

        $user->favoriteOffers()->attach($data['offer_id']);
        return ['message' => 'Offer added to favorites'];
    }

    public function destroy(int $id)
    {
        $user = Auth::user();
        $user->favoriteOffers()->detach($id);
        return ['message' => 'Offer removed from favorites'];
    }

    public function toggleFavorite(array $data)
    {
        $user = Auth::user();
        $offerId = $data['offer_id'];

        $exists = $user->favoriteOffers()->where('offer_id', $offerId)->exists();

        if ($exists) {
            $user->favoriteOffers()->detach($offerId);
            return [
                'status' => true,
                'is_favorite' => false,
                'message' => 'Offer removed from favorites'
            ];
        } else {
            $user->favoriteOffers()->attach($offerId);
            return [
                'status' => true,
                'is_favorite' => true,
                'message' => 'Offer added to favorites'
            ];
        }
    }
}
