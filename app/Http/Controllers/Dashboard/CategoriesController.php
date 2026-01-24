<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\Dashboard\CategoriesRepositoryInterface;
use App\Http\Requests\Dashboard\Categories\StoreCategoryRequest;
use App\Http\Requests\Dashboard\Categories\UpdateCategoryRequest;

class CategoriesController extends Controller
{
    public function __construct(private CategoriesRepositoryInterface $repo) {}

    public function index()
    {
        $rows = $this->repo->paginate([
            'search' => request('search'),
        ], (int) request('per_page', 10));

        return view('dashboard.categories.index', compact('rows'));
    }

    public function create()
    {
        return view('dashboard.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $this->repo->create($data);

        return redirect()->route('dashboard.categories.index')->with('success', 'Category created');
    }

    public function edit($category)
    {
        $row = $this->repo->findOrFail((int)$category);
        return view('dashboard.categories.edit', compact('row'));
    }

    public function update(UpdateCategoryRequest $request, $category)
    {
        $row = $this->repo->findOrFail((int)$category);
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($row->image) Storage::disk('public')->delete($row->image);
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $this->repo->update((int)$category, $data);

        return redirect()->route('dashboard.categories.index')->with('success', 'Category updated');
    }

    public function destroy($category)
    {
        $row = $this->repo->findOrFail((int)$category);
        if ($row->image) Storage::disk('public')->delete($row->image);

        $this->repo->delete((int)$category);

        return back()->with('success', 'Category deleted');
    }
}
