<?php

namespace App\Repositories\Offer;


use App\Models\OfferComplaint;
use App\Interfaces\Offer\OfferComplaintRepositoryInterface;

class OfferComplaintRepository implements OfferComplaintRepositoryInterface
{
    public function all()
    {
        return OfferComplaint::with(['user', 'offer'])->latest()->get();
    }

    public function find($id)
    {
        return OfferComplaint::with(['user', 'offer'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return OfferComplaint::create($data);
    }

    public function update(array $data, $id)
    {
        $complaint = OfferComplaint::findOrFail($id);
        $complaint->update($data);
        return $complaint;
    }

    public function delete($id)
    {
        return OfferComplaint::destroy($id);
    }

    // ✅ Get complaints created by the user
    public function getMyComplaints($userId)
    {
        return OfferComplaint::with('offer')
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    // ✅ Get complaints for offers owned by the user
    public function getComplaintsOnMyOffers($userId)
    {
        return OfferComplaint::with(['offer', 'user'])
            ->whereHas('offer', fn($q) => $q->where('user_id', $userId))
            ->latest()
            ->get();
    }
}
