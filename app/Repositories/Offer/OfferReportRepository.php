<?php

namespace App\Repositories\Offer;



use App\Models\OfferReport;
use App\Interfaces\Offer\OfferReportRepositoryInterface;

class OfferReportRepository implements OfferReportRepositoryInterface
{
    public function all()
    {
        return OfferReport::with(['user', 'offer'])->latest()->get();
    }

    public function find($id)
    {
        return OfferReport::with(['user', 'offer'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return OfferReport::create($data);
    }

    public function update(array $data, $id)
    {
        $report = OfferReport::findOrFail($id);
        $report->update($data);
        return $report;
    }

    public function delete($id)
    {
        return OfferReport::destroy($id);
    }

    // ✅ Get reports created by the user
    public function getMyReports($userId)
    {
        return OfferReport::with('offer')
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    // ✅ Get reports for offers owned by the user
    public function getReportsOnMyOffers($userId)
    {
        return OfferReport::with(['offer', 'user'])
            ->whereHas('offer', fn($q) => $q->where('user_id', $userId))
            ->latest()
            ->get();
    }
}
