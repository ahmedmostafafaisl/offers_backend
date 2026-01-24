<?php

namespace App\Interfaces\Slider;

interface SliderRepositoryInterface
{
    public function all();
    public function find($id);
    public function store(array $data);
    public function update(int $id, array $data);
    public function destroy(int $id);
}
