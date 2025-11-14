<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        $search = $request->string('q');
        if ($search->isNotEmpty()) {
            $term = (string) $search;
            $query->where('name', 'like', "%{$term}%");
        }

        $category = $request->string('category');
        if ($category->isNotEmpty()) {
            $query->where('category', (string) $category);
        } else {
            // Optional category grouping, e.g., pet food group
            $categoryGroup = $request->string('category_group');
            if ($categoryGroup->isNotEmpty()) {
                $group = strtolower((string) $categoryGroup);
                if ($group === 'petfood') {
                    $query->where(function ($q) {
                        $q->where('category', 'like', '%Food%')
                          ->orWhereIn('category', ['Cat Food', 'Dog Food', 'Pet Food']);
                    });
                } elseif ($group === 'supplies') {
                    $query->where(function ($q) {
                        $q->where('category', 'like', '%Suppl%')
                          ->orWhere('category', 'Supplies');
                    });
                } elseif ($group === 'vitamin' || $group === 'vitamins') {
                    $query->where(function ($q) {
                        $q->where('category', 'like', '%Vitamin%')
                          ->orWhere('category', 'Vitamin');
                    });
                }
            }
        }

        $status = $request->string('status');
        if ($status->isNotEmpty()) {
            $query->where('status', (string) $status);
        }

        // Sorting
        $sort = strtolower((string) $request->string('sort'));
        $direction = strtolower((string) $request->string('direction'));
        $allowedSorts = ['price', 'name', 'stock', 'created_at'];
        $allowedDirections = ['asc', 'desc'];

        if (in_array($sort, $allowedSorts, true) && in_array($direction, $allowedDirections, true)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->latest();
        }

        $perPage = max(1, min(100, (int) $request->integer('per_page', 10)));
        $products = $query->paginate($perPage);

        return response()->json($products);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug'],
            'category' => ['required', 'string', 'max:100'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['available', 'coming-soon', 'preorder'])],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image_path'] = $path;
        }

        $product = Product::create($data);

        return response()->json([
            'message' => 'Product created successfully',
            'data' => $product,
        ], 201);
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        return response()->json($product);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($product->id)],
            'category' => ['sometimes', 'required', 'string', 'max:100'],
            'sku' => ['sometimes', 'required', 'string', 'max:100', Rule::unique('products', 'sku')->ignore($product->id)],
            'price' => ['sometimes', 'required', 'integer', 'min:0'],
            'stock' => ['sometimes', 'required', 'integer', 'min:0'],
            'status' => ['sometimes', 'required', Rule::in(['available', 'coming-soon', 'preorder'])],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }
            $path = $request->file('image')->store('products', 'public');
            $data['image_path'] = $path;
        }

        $product->update($data);

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $product->refresh(),
        ]);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
