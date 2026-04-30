@extends('layouts.app')

@section('title', 'Daftar Peminjaman')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Daftar Peminjaman</h1>
        <a href="{{ route('loans.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
            + Peminjaman Baru
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Peminjam
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Buku
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Tanggal Pinjam
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Jatuh Tempo
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($loans as $loan)
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap font-semibold">{{ $loan->user->name }}</p>
                        <p class="text-gray-600 text-xs">{{ $loan->user->email }}</p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap font-semibold">{{ $loan->book->title }}</p>
                        <p class="text-gray-600 text-xs">{{ $loan->book->author }}</p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">{{ $loan->loan_date->format('d M Y') }}</p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="{{ $loan->isOverdue() ? 'text-red-600 font-bold' : 'text-gray-900' }}">
                            {{ $loan->due_date->format('d M Y') }}
                            @if($loan->isOverdue())
                                <br><span class="text-xs">(Terlambat)</span>
                            @endif
                        </p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <span class="relative inline-block px-3 py-1 font-semibold leading-tight {{ $loan->status === 'returned' ? 'text-green-900' : ($loan->isOverdue() ? 'text-red-900' : 'text-yellow-900') }}">
                            <span aria-hidden="true" class="absolute inset-0 {{ $loan->status === 'returned' ? 'bg-green-200' : ($loan->isOverdue() ? 'bg-red-200' : 'bg-yellow-200') }} opacity-50 rounded-full"></span>
                            <span class="relative">{{ ucfirst($loan->status) }}</span>
                        </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <div class="flex space-x-2">
                            <a href="{{ route('loans.show', $loan->id) }}" class="text-blue-600 hover:text-blue-900">
                                Detail
                            </a>
                            @if($loan->status === 'borrowed')
                                <form action="{{ route('loans.return', $loan->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900">
                                        Kembalikan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                        Belum ada data peminjaman.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $loans->links() }}
    </div>
</div>
@endsection
