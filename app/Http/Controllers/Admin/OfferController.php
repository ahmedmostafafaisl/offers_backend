<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Offer\OfferResource;
use App\Interfaces\Offer\OfferRepositoryInterface;
use App\Http\Requests\Offer\{StoreOfferRequest, UpdateOfferRequest};

class OfferController extends Controller
{
    protected $offerRepository;

    public function __construct(OfferRepositoryInterface $offerRepository)
    {
        $this->offerRepository = $offerRepository;
        $this->middleware('auth:sanctum')->only(['store', 'update']);
    }

    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $pageSize = $request->input('page_size', 10);

        $offers = $this->offerRepository->all($request->input('user_id') ?? null, $request->input('category_id') ?? null, $request->input('city') ?? null, $page, $pageSize);

        return response()->json([
            'data' => OfferResource::collection($offers->items()),
            'current_page' => $offers->currentPage(),
            'page_size' => $offers->perPage(),
            'total' => $offers->total()
        ]);
    }


    public function store(StoreOfferRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $offer = $this->offerRepository->store($data);
        try {
            event(new \App\Events\OfferCreated($offer));
        } catch (\Exception $e) {
            Log::error('Failed to dispatch SendOfferCreatedNotification job: ' . $e->getMessage());
        }

        return new OfferResource($offer);
    }

    public function show($id)
    {
        return new OfferResource($this->offerRepository->find($id));
    }

    public function update(UpdateOfferRequest $request, $id)
    {
        $offer = $this->offerRepository->update($id, $request->validated());
        return new OfferResource($offer);
    }

    public function destroy($id)
    {
        $this->offerRepository->delete($id);
        return response()->json(['message' => 'Offer deleted successfully']);
    }

    //  provider offers
    public function providerOffers(Request $request)
    {
        $offers = $this->offerRepository->providerOffers($request->input('status'), $request->input('search'));
        return OfferResource::collection($offers);
    }

    // user stats
    public function providerStats()
    {

        $stats = $this->offerRepository->providerStats();

        return response()->json($stats);
    }
    public function interact(Request $request, int $offerId): JsonResponse
    {
        $request->validate([
            'type' => 'required|in:view,like',
        ]);

        $success = $this->offerRepository->incrementInteraction($offerId, $request->input('type'));

        if (! $success) {
            return response()->json(['message' => 'Invalid offer or type'], 400);
        }

        return response()->json([
            'message' => ucfirst($request->input('type')) . ' added successfully',
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string',
            'user_id' => 'nullable|integer|exists:users,id',
        ]);

        $result = $this->offerRepository->searchOffersAndUsers($request->name, $request->user_id);

        return response()->json([
            'status' => true,
            'message' => 'Search results retrieved successfully',
            'offers' => OfferResource::collection($result['offers']),
            'users' => $result['users'],
        ]);
    }
}
