<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Menampilkan daftar buku dengan paginasi dan eager loading kategori.
     */
    public function index()
    {
        $books = Book::with('category')
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);

        return view('books.index', compact('books'));
    }

    /**
     * Menampilkan form tambah buku baru.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('books.create', compact('categories'));
    }

    /**
     * Menyimpan buku baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'required|string|max:255',
            'year'        => 'nullable|integer|min:1000|max:' . date('Y'),
            'category_id' => 'nullable|exists:categories,id',
        ]);

        Book::create($validated);

        return redirect()->route('admin.books.index')
                         ->with('success', 'Buku "' . $validated['title'] . '" berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail satu buku.
     */
    public function show(string $id)
    {
        $book = Book::with('category')->findOrFail($id);
        return view('books.show', compact('book'));
    }

    /**
     * Menampilkan form edit buku.
     */
    public function edit(string $id)
    {
        $book = Book::findOrFail($id);
        $categories = Category::orderBy('name')->get();
        return view('books.edit', compact('book', 'categories'));
    }

    /**
     * Memperbarui data buku.
     */
    public function update(Request $request, string $id)
    {
        $book = Book::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'required|string|max:255',
            'year'        => 'nullable|integer|min:1000|max:' . date('Y'),
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $book->update($validated);

        return redirect()->route('admin.books.index')
                         ->with('success', 'Buku "' . $book->title . '" berhasil diperbarui!');
    }

    /**
     * Menghapus buku.
     */
    public function destroy(string $id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return redirect()->route('admin.books.index')
                         ->with('success', 'Buku "' . $book->title . '" berhasil dihapus!');
    }
}
