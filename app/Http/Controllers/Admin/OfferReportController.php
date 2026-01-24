<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Offer\OfferReportRequest;
use App\Http\Resources\Offer\OfferReportResource;
use App\Interfaces\Offer\OfferReportRepositoryInterface;

class OfferReportController extends Controller
{
    protected $repo;

    public function __construct(OfferReportRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        return OfferReportResource::collection($this->repo->all());
    }

    public function store(OfferReportRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $report = $this->repo->create($data);
        return new OfferReportResource($report);
    }

    public function show($id)
    {
        return new OfferReportResource($this->repo->find($id));
    }

    public function update(OfferReportRequest $request, $id)
    {
        $report = $this->repo->update($request->validated(), $id);
        return new OfferReportResource($report);
    }

    public function destroy($id)
    {
        $this->repo->delete($id);
        return response()->json(['message' => 'Offer report deleted successfully']);
    }

    // ✅ 1. Get my reports
    public function myReports()
    {
        $userId = auth()->id();
        $reports = $this->repo->getMyReports($userId);

        return response()->json([
            'status' => true,
            'message' => 'My reports retrieved successfully',
            'data' => $reports,
        ]);
    }

    // ✅ 2. Get reports on my offers
    public function reportsOnMyOffers()
    {
        $userId = auth()->id();
        $reports = $this->repo->getReportsOnMyOffers($userId);

        return response()->json([
            'status' => true,
            'message' => 'Reports on your offers retrieved successfully',
            'data' => $reports,
        ]);
    }
}
