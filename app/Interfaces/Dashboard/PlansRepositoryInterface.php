<?php

namespace App\Interfaces\Dashboard;

interface PlansRepositoryInterface extends BaseRepositoryInterface
{
    // ✅ create/update plan with its features (one module)
    public function createWithFeatures(array $data);
    public function updateWithFeatures(int $id, array $data);
}
