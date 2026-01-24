<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Offer\OfferResource;
use App\Http\Resources\User\FavoriteOfferResource;
use App\Repositories\User\FavoriteOfferRepository;
use App\Http\Requests\User\FavoriteOfferStoreRequest;

class FavoriteOfferController extends Controller
{
    protected $favoriteRepo;

    public function __construct(FavoriteOfferRepository $favoriteRepo)
    {
        $this->favoriteRepo = $favoriteRepo;
    }

    public function index(): JsonResponse
    {
        $favorites = $this->favoriteRepo->index();
        return response()->json([
            'status' => true,
            'data' => OfferResource::collection($favorites),
        ]);
    }

    public function store(FavoriteOfferStoreRequest $request): JsonResponse
    {
        $result = $this->favoriteRepo->store($request->validated());
        return response()->json(['status' => true, 'message' => $result['message']]);
    }

    public function destroy($id): JsonResponse
    {
        $result = $this->favoriteRepo->destroy($id);
        return response()->json(['status' => true, 'message' => $result['message']]);
    }

    public function toggleFavorite(FavoriteOfferStoreRequest $request): JsonResponse
    {
        $result = $this->favoriteRepo->toggleFavorite($request->validated());
        return response()->json($result);
    }
}
