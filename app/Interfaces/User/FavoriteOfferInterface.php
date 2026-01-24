<?php

namespace App\Interfaces\User;

interface FavoriteOfferInterface
{
    public function index();
    public function store(array $data);
    public function destroy(int $id);
    public function toggleFavorite(array $data);
}
