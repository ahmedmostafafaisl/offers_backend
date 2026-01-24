<?php

namespace App\Repositories\Slider;

use App\Models\Slider;
use App\Interfaces\Slider\SliderRepositoryInterface;

class SliderRepository implements SliderRepositoryInterface
{
    public function all()
    {
        return Slider::all();
    }

    public function find($id)
    {
        return Slider::findOrFail($id);
    }

    public function store(array $data)
    {
        return Slider::create($data);
    }

    public function update(int $id, array $data)
    {
        $slider = $this->find($id);
        $slider->update($data);
        return $slider;
    }

    public function destroy(int $id)
    {
        $slider = $this->find($id);
        $slider->delete();
        return true;
    }
}
