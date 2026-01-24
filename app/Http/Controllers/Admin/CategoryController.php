<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Interfaces\Category\CategoryRepositoryInterface;

class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $categories = $this->categoryRepository->all();
        return response()->json(CategoryResource::collection($categories));
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category = $this->categoryRepository->create($data);
        return response()->json(new CategoryResource($category), 201);
    }

    public function show($id)
    {
        $category = $this->categoryRepository->find($id);
        return response()->json(new CategoryResource($category));
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category = $this->categoryRepository->update($id, $data);
        return response()->json(new CategoryResource($category));
    }

    public function destroy($id)
    {
        $this->categoryRepository->delete($id);
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
