<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductIndexRequest;
use App\Models\Product;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class ProductController extends Controller
{
  public function index(ProductIndexRequest $request)
  {

    // adding a limit to products per page
    $perPage = min($request->input('per_page', 50), 100);


    $query = Product::with('category');

    // get the category needed
    if ($request->has('category_id')) {
      $query->where('categoryId', $request->input('category_id'));
    }


    // search by name or description
    if ($request->has('search')) {
      $searchTerm = $request->input('search');
      $query->where(function ($q) use ($searchTerm) {
        $q->where('nom', 'like', '%' . $searchTerm . '%')
          ->orWhere('description', 'like', '%' . $searchTerm . '%');
      });
    }


    // filtering by price , type , date
    if ($request->has('min_price')) {
      $query->where('prix', '>=', $request->input('min_price'));
    }

    if ($request->has('max_price')) {
      $query->where('prix', '<=', $request->input('max_price'));
    }



    if ($request->has('type')) {
      $query->where('type', 'like', '%' . $request->input('type') . '%');
    }


    $sortBy = $request->input('sort_by', 'created_at');
    $sortDirection = $request->input('sort_dir', 'desc');
    $query->orderBy($sortBy, $sortDirection);

    // paginate the products
    $products = $query->paginate($perPage);


    $products->getCollection()->transform(function ($product) {
      return $product;
    });


    // return an organized json object for proper pagination
    return response()->json([
      'data' => $products->items(),
      'meta' => [
        'current_page' => $products->currentPage(),
        'per_page' => $products->perPage(),
        'total' => $products->total(),
        'last_page' => $products->lastPage(),
        'sort_by' => $sortBy,
        'sort_dir' => $sortDirection,
        'filters' => $request->only(['category_id', 'search', 'min_price', 'max_price',  'type'])
      ],

      // pagination Links
      'links' => [
        'first' => $products->url(1),
        'last' => $products->url($products->lastPage()),
        'prev' => $products->previousPageUrl(),
        'next' => $products->nextPageUrl(),
      ]
    ]);
  }




  public function store(Request $request)
  {
    $this->authorize('create', Product::class);
    $validated = $request->validate([
      'nom' => 'required|string|max:255',
      'description' => 'nullable|string',
      'prix' => 'required|numeric|min:0',
      'stock' => 'required|integer|min:0',
      'categoryId' => 'required|exists:categories,categoryId',
      'type' => 'nullable|string',
      'size' => 'nullable|string',
      'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:1048',
    ]);
    $imageUrl = null;
    $imagePublicId = null;

    if ($request->hasFile('image')) {
      $cloudinaryUrl = config('cloudinary.cloud_url');
      if (!$cloudinaryUrl) {
        return response()->json([
          'error' => 'Cloudinary configuration is missing. Please set CLOUDINARY_URL in your .env file.'
        ], 500);
      }

      try {
        $cloudinary = new Cloudinary($cloudinaryUrl);
        $uploadApi = $cloudinary->uploadApi();
        $uploaded = $uploadApi->upload(
          $request->file('image')->getRealPath(),
          ['folder' => 'products']
        );
        $imageUrl = $uploaded['secure_url'] ?? null;
        $imagePublicId = $uploaded['public_id'] ?? null;
      } catch (\Exception $e) {
        return response()->json([
          'error' => 'Error uploading image: ' . $e->getMessage()
        ], 500);
      }
    }

    $product = Product::create([
      'nom' => $validated['nom'],
      'description' => $validated['description'] ?? null,
      'prix' => $validated['prix'],
      'stock' => $validated['stock'],
      'categoryId' => $validated['categoryId'],
      'type' => $validated['type'] ?? null,
      'size' => $validated['size'] ?? null,
      'imageUrl' => $imageUrl ?? null,
      'image_public_id' => $imagePublicId
    ]);

    return response()->json($product, 201);
  }


  public function show($id)
  {
    $product = Product::with('category')->findOrFail($id);
    return $product;
  }

  public function update(Request $request, string $id)
  {
    $product = Product::findOrFail($id);
    $this->authorize('update', $product);
    $validated = $request->validate([
      'nom' => 'required|string|max:255',
      'description' => 'nullable|string',
      'prix' => 'required|numeric|min:0',
      'stock' => 'required|integer|min:0',
      'categoryId' => 'required|exists:categories,categoryId',
      'type' => 'nullable|string',
      'size' => 'nullable|string',
      'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:1048',
    ]);

    $imageUrl = $product->imageUrl;
    $imagePublicId = $product->image_public_id;

    if ($request->hasFile('image')) {
      $cloudinaryUrl = config('cloudinary.cloud_url');
      if (!$cloudinaryUrl) {
        return response()->json([
          'error' => 'Cloudinary configuration is missing. Please set CLOUDINARY_URL in your .env file.'
        ], 500);
      }

      try {
        $cloudinary = new Cloudinary($cloudinaryUrl);
        $uploadApi = $cloudinary->uploadApi();
        if ($product->image_public_id) {
          $uploadApi->destroy($product->image_public_id);
        }
        $uploaded = $uploadApi->upload(
          $request->file('image')->getRealPath(),
          ['folder' => 'products']
        );
        $imageUrl = $uploaded['secure_url'] ?? null;
        $imagePublicId = $uploaded['public_id'] ?? null;
      } catch (\Exception $e) {
        return response()->json([
          'error' => 'Error uploading image: ' . $e->getMessage()
        ], 500);
      }
    }

    $product->update([
      'nom' => $validated['nom'],
      'description' => $validated['description'],
      'prix' => $validated['prix'],
      'stock' => $validated['stock'],
      'categoryId' => $validated['categoryId'],
      'type' => $validated['type'],
      'size' => $validated['size'] ?? null,
      'imageUrl' => $imageUrl,
      'image_public_id' => $imagePublicId
    ]);
    return response()->json($product, 200);
  }

  public function destroy($id)
  {
    $product = Product::findOrFail($id);
    $this->authorize('delete', $product);

    if ($product->image_public_id) {
      $cloudinaryUrl = config('cloudinary.cloud_url');
      if ($cloudinaryUrl) {
        try {
          $cloudinary = new Cloudinary($cloudinaryUrl);
          $uploadApi = $cloudinary->uploadApi();
          $uploadApi->destroy($product->image_public_id);
        } catch (\Exception $e) {
          // Log error but don't fail the delete operation
          Log::error('Error deleting image from Cloudinary: ' . $e->getMessage());
        }
      }
    }

    $product->delete();
    return response()->json(null, 204);
  }
}
