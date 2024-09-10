<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use App\Traits\ResponseHandlerTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    use ResponseHandlerTrait;


    public function index(): Collection|array
    {
        return Category::whereNull('parent_id')->with('children','products')->get();
    }


    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        Category::create($validatedData);

        return $this->successResponse('Category created successfully',201);
    }



    public function show(Category $category ): JsonResponse
    {
        return $this->successWithDataResponse($category->load('children','products'));
    }



    public function update(StoreCategoryRequest $request, Category $category): JsonResponse
    {
        $category->update($request->validated());

        return $this->successResponse('Category updated successfully');
    }


    public function destroy(Category $category): JsonResponse
    {
        $category->delete();
        return $this->successResponse('Category deleted successfully');
    }

}
