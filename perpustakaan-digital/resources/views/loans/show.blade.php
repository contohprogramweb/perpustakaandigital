@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('loans.index') }}" class="text-blue-600 hover:text-blue-900">&larr; Kembali ke Daftar Peminjaman</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Loan Info Card -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Peminjaman</h2>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">ID Peminjaman</p>
                    <p class="font-semibold">#{{ $loan->id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $loan->status === 'returned' ? 'bg-green-200 text-green-900' : ($loan->isOverdue() ? 'bg-red-200 text-red-900' : 'bg-yellow-200 text-yellow-900') }}">
                        {{ ucfirst($loan->status) }}
                        @if($loan->isOverdue())
                            (Terlambat)
                        @endif
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tanggal Pinjam</p>
                    <p class="font-semibold">{{ $loan->loan_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Jatuh Tempo</p>
                    <p class="font-semibold {{ $loan->isOverdue() ? 'text-red-600' : '' }}">{{ $loan->due_date->format('d M Y') }}</p>
                </div>
                @if($loan->return_date)
                <div>
                    <p class="text-sm text-gray-500">Tanggal Kembali</p>
                    <p class="font-semibold text-green-600">{{ $loan->return_date->format('d M Y') }}</p>
                </div>
                @endif
                @if($loan->notes)
                <div>
                    <p class="text-sm text-gray-500">Catatan</p>
                    <p class="font-semibold">{{ $loan->notes }}</p>
                </div>
                @endif
            </div>

            @if($loan->status === 'borrowed')
                <div class="mt-6">
                    <form action="{{ route('loans.return', $loan->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-md transition duration-200 font-semibold">
                            ✓ Kembalikan Buku
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Borrower Info Card -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Peminjam</h2>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Nama</p>
                    <p class="font-semibold">{{ $loan->user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-semibold">{{ $loan->user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Role</p>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $loan->user->role === 'admin' ? 'bg-purple-200 text-purple-900' : 'bg-blue-200 text-blue-900' }}">
                        {{ ucfirst($loan->user->role) }}
                    </span>
                </div>
                @if($loan->user->phone)
                <div>
                    <p class="text-sm text-gray-500">Telepon</p>
                    <p class="font-semibold">{{ $loan->user->phone }}</p>
                </div>
                @endif
            </div>
            <a href="{{ route('users.show', $loan->user->id) }}" class="mt-4 block text-center bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md transition duration-200">
                Lihat Profil Peminjam
            </a>
        </div>
    </div>

    <!-- Book Info Card -->
    <div class="bg-white shadow-md rounded-lg p-6 mt-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Buku</h2>
        <div class="flex items-start space-x-4">
            @if($loan->book->cover_image)
                <img src="{{ $loan->book->cover_image }}" alt="{{ $loan->book->title }}" class="w-32 h-48 object-cover rounded-lg">
            @else
                <div class="w-32 h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                    <span class="text-4xl">📚</span>
                </div>
            @endif
            <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-800">{{ $loan->book->title }}</h3>
                <p class="text-gray-600 mb-2">Oleh: {{ $loan->book->author }}</p>
                @if($loan->book->category)
                    <p class="text-sm text-gray-500 mb-2">
                        Kategori: <span class="font-medium">{{ $loan->book->category->name }}</span>
                    </p>
                @endif
                <p class="text-sm text-gray-500 mb-2">
                    Stok Tersedia: <span class="font-medium {{ $loan->book->available_stock > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $loan->book->available_stock }} / {{ $loan->book->stock }}</span>
                </p>
                @if($loan->book->published_year)
                    <p class="text-sm text-gray-500 mb-2">
                        Tahun Terbit: <span class="font-medium">{{ $loan->book->published_year }}</span>
                    </p>
                @endif
                @if($loan->book->description)
                    <p class="text-sm text-gray-600 mt-4">{{ Str::limit($loan->book->description, 200) }}</p>
                @endif
                <a href="{{ route('books.show', $loan->book->id) }}" class="mt-4 inline-block bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md transition duration-200">
                    Lihat Detail Buku
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
