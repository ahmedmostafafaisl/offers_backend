<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\Dashboard\SlidersRepositoryInterface;
use App\Http\Requests\Dashboard\Sliders\StoreSliderRequest;
use App\Http\Requests\Dashboard\Sliders\UpdateSliderRequest;

class SlidersController extends Controller
{
    public function __construct(private SlidersRepositoryInterface $repo) {}

    public function index()
    {
        $rows = $this->repo->paginate([
            'search' => request('search'),
            'status' => request('status'),
        ], (int) request('per_page', 10));

        return view('dashboard.sliders.index', compact('rows'));
    }

    public function create()
    {
        return view('dashboard.sliders.create');
    }

    public function store(StoreSliderRequest $request)
    {
        $data = $request->validated();
        $data['status'] = (bool)($data['status'] ?? true);

        $data['image'] = $request->file('image')->store('sliders', 'public');

        $this->repo->create($data);

        return redirect()->route('dashboard.sliders.index')->with('success', 'Slider created');
    }

    public function edit($slider)
    {
        $row = $this->repo->findOrFail((int)$slider);
        return view('dashboard.sliders.edit', compact('row'));
    }

    public function update(UpdateSliderRequest $request, $slider)
    {
        $row = $this->repo->findOrFail((int)$slider);
        $data = $request->validated();
        $data['status'] = (bool)($data['status'] ?? false);

        if ($request->hasFile('image')) {
            if ($row->image) Storage::disk('public')->delete($row->image);
            $data['image'] = $request->file('image')->store('sliders', 'public');
        }

        $this->repo->update((int)$slider, $data);

        return redirect()->route('dashboard.sliders.index')->with('success', 'Slider updated');
    }

    public function destroy($slider)
    {
        $row = $this->repo->findOrFail((int)$slider);
        if ($row->image) Storage::disk('public')->delete($row->image);

        $this->repo->delete((int)$slider);

        return back()->with('success', 'Slider deleted');
    }
}
