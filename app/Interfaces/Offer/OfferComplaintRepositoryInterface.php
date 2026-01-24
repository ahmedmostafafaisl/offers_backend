<?php

namespace App\Interfaces\Offer;


interface OfferComplaintRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update(array $data, $id);
    public function delete($id);

    // ✅ New methods
    public function getMyComplaints($userId);
    public function getComplaintsOnMyOffers($userId);
}
