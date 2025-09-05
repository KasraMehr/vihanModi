<?php

namespace App\Http\Controllers\Translator;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    /**
     * Displays a list of categories along with their word count.
     */
    public function index(): Response
    {
        $categories = Category::withCount('words')->get();

        return Inertia::render('Translator/Words/categories', [
            'categories' => $categories,
        ]);
    }

    /**
     * Retrieves a specific category along with its associated words.
     *
     * @param  int  $id
     */
    public function getCategoryWords($id): JsonResponse
    {
        $category = Category::with('words')->findOrFail($id);

        return response()->json(['category' => $category]);
    }

    /**
     * validates the category.
     */
    private function validateCategory(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|unique:categories,name|max:255',
            'description' => 'nullable|string|max:1000',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateCategory($request);

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($request->name),
            'description' => $validated['description'] ?? null,
        ]);

        return response()->json(['message' => 'category created successfully', 'category' => $category], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     */
    public function update(Request $request, $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => "required|string|unique:categories,name,{$category->id}",
            'description' => 'nullable|string',
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $request->filled('description') ? $validated['description'] : $category->description,
        ]);

        return response()->json(['message' => 'category updated successfully', 'category' => $category]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id): JsonResponse
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'category deleted successfully']);
    }
}
