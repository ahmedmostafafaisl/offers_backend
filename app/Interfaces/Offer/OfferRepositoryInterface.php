<?php

namespace App\Interfaces\Offer;

interface OfferRepositoryInterface
{
    public function all($userId = null, $categoryId = null, $city = null, $page = 1, $pageSize = 10);
    public function find($id);
    public function store(array $data);
    public function update($id, array $data);
    public function delete($id);

    public function providerOffers($status, $search = null);
    public function providerStats();
    public function incrementInteraction(int $offerId, string $type): bool;
    public function searchOffersAndUsers(?string $name, ?int $userId);
}
