<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

  public function index()
  {
    return Category::all();
  }

  public function store(Request $request)
  {
    $this->authorize('create', Category::class);
    $request->validate([
      'name' => 'required|string|max:50',
    ]);

    $category = Category::create($request->all());
    return response()->json($category, 201);
  }


  public function show(Category $category)
  {
    return response()->json($category);
  }

  public function update(Request $request, Category $category)
  {
    $this->authorize('update', $category);
    $request->validate([
      'name' => 'required|string|max:50',
    ]);

    $category->update($request->only('name'));
    return response()->json($category);
  }

  public function destroy(Category $category)
  {
    $this->authorize('delete', $category);
    $category->delete();
    return response()->json(null, 204);
  }
}
