@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Detail Buku</h1>
            <a href="{{ route('books.index') }}" class="text-blue-600 hover:text-blue-900">
                &larr; Kembali ke Daftar Buku
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="md:flex">
                @if($book->cover_image)
                <div class="md:w-1/3">
                    <img src="{{ $book->cover_image }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                </div>
                @endif
                <div class="p-6 md:w-2/3">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $book->title }}</h2>
                    <p class="text-gray-600 mb-4">oleh {{ $book->author }}</p>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <span class="font-semibold text-gray-700">ISBN:</span>
                            <p class="text-gray-600">{{ $book->isbn ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-700">Penerbit:</span>
                            <p class="text-gray-600">{{ $book->publisher ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-700">Tahun Terbit:</span>
                            <p class="text-gray-600">{{ $book->published_year ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-700">Kategori:</span>
                            <p class="text-gray-600">{{ $book->category ? $book->category->name : '-' }}</p>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-700">Stok:</span>
                            <p class="text-gray-600">{{ $book->available_stock }} / {{ $book->stock }}</p>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-700">Status:</span>
                            <span class="{{ $book->isAvailable() ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                {{ $book->isAvailable() ? 'Tersedia' : 'Habis' }}
                            </span>
                        </div>
                    </div>

                    @if($book->description)
                    <div class="mb-4">
                        <span class="font-semibold text-gray-700 block mb-2">Deskripsi:</span>
                        <p class="text-gray-600">{{ $book->description }}</p>
                    </div>
                    @endif

                    <div class="flex space-x-2 mt-6">
                        <a href="{{ route('books.edit', $book->id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition duration-200">
                            Edit
                        </a>
                        <form action="{{ route('books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-200">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
