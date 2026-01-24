<?php

namespace App\Interfaces\Offer;


interface OfferReportRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update(array $data, $id);
    public function delete($id);

    // ✅ New methods
    public function getMyReports($userId);
    public function getReportsOnMyOffers($userId);
}
