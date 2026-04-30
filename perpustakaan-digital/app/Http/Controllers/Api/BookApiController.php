<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BookApiController extends Controller
{
    /**
     * Display a listing of books.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Book::with('category');

        // Search functionality
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by availability
        if ($request->has('available') && $request->boolean('available')) {
            $query->available();
        }

        $books = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $books,
        ]);
    }

    /**
     * Store a newly created book.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn',
            'publisher' => 'nullable|string|max:255',
            'published_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $validated['available_stock'] = $validated['stock'];

        $book = Book::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Book created successfully.',
            'data' => $book->fresh(),
        ], 201);
    }

    /**
     * Display the specified book.
     */
    public function show(Book $book): JsonResponse
    {
        $book->load(['category', 'loans.user']);

        return response()->json([
            'success' => true,
            'data' => $book,
        ]);
    }

    /**
     * Update the specified book.
     */
    public function update(Request $request, Book $book): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'isbn' => 'sometimes|required|string|unique:books,isbn,' . $book->id,
            'publisher' => 'nullable|string|max:255',
            'published_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'category_id' => 'sometimes|required|exists:categories,id',
            'stock' => 'sometimes|required|integer|min:0',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('cover_image')) {
            // Delete old image if exists
            if ($book->cover_image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($book->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Book updated successfully.',
            'data' => $book->fresh(),
        ]);
    }

    /**
     * Remove the specified book.
     */
    public function destroy(Book $book): JsonResponse
    {
        if ($book->loans()->where('status', 'borrowed')->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete book with active loans.',
            ], 422);
        }

        // Delete cover image if exists
        if ($book->cover_image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return response()->json([
            'success' => true,
            'message' => 'Book deleted successfully.',
        ]);
    }
}
