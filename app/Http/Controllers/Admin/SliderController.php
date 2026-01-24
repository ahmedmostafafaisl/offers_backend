<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Slider\SliderResource;
use App\Interfaces\Slider\SliderRepositoryInterface;

class SliderController extends Controller
{
    private $sliderRepository;

    public function __construct(SliderRepositoryInterface $sliderRepository)
    {
        $this->sliderRepository = $sliderRepository;
    }

    public function index(Request $request)
    {

        $sliders = $this->sliderRepository->all();
        return SliderResource::collection($sliders);
    }

    public function show($id)
    {
        $slider = $this->sliderRepository->find($id);
        return new SliderResource($slider);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'boolean',
        ]);

        // Save image
        $path = $request->file('image')->store('sliders', 'public');
        $data['image'] = $path;

        $slider = $this->sliderRepository->store($data);
        return response()->json([
            'message' => 'Slider created successfully',
            'data' => new SliderResource($slider),
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'sometimes|string',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'sometimes|boolean',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('sliders', 'public');
            $data['image'] = $path;
        }

        $slider = $this->sliderRepository->update($id, $data);
        return response()->json([
            'message' => 'Slider updated successfully',
            'data' => new SliderResource($slider),
        ]);
    }

    public function destroy($id)
    {
        $this->sliderRepository->destroy($id);
        return response()->json(['message' => 'Slider deleted successfully']);
    }
}
