<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Offer\OfferComplaintRequest;
use App\Http\Resources\Offer\OfferComplaintResource;
use App\Interfaces\Offer\OfferComplaintRepositoryInterface;

class OfferComplaintController extends Controller
{
    protected $repo;

    public function __construct(OfferComplaintRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        return OfferComplaintResource::collection($this->repo->all());
    }

    public function store(OfferComplaintRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $complaint = $this->repo->create($data);
        return new OfferComplaintResource($complaint);
    }

    public function show($id)
    {
        return new OfferComplaintResource($this->repo->find($id));
    }

    public function update(OfferComplaintRequest $request, $id)
    {
        $complaint = $this->repo->update($request->validated(), $id);
        return new OfferComplaintResource($complaint);
    }

    public function destroy($id)
    {
        $this->repo->delete($id);
        return response()->json(['message' => 'Offer complaint deleted successfully']);
    }

    // ✅ 1. Get my complaints
    public function myComplaints()
    {
        $userId = auth()->id();
        $complaints = $this->repo->getMyComplaints($userId);

        return response()->json([
            'status' => true,
            'message' => 'My complaints retrieved successfully',
            'data' => $complaints,
        ]);
    }

    // ✅ 2. Get complaints on my offers
    public function complaintsOnMyOffers()
    {
        $userId = auth()->id();
        $complaints = $this->repo->getComplaintsOnMyOffers($userId);

        return response()->json([
            'status' => true,
            'message' => 'Complaints on your offers retrieved successfully',
            'data' => $complaints,
        ]);
    }
}
