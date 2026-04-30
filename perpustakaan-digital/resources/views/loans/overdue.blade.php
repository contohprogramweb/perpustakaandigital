@extends('layouts.app')

@section('title', 'Peminjaman Terlambat')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('loans.index') }}" class="text-blue-600 hover:text-blue-900">&larr; Kembali ke Daftar Peminjaman</a>
    </div>

    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p class="font-bold">Peminjaman Terlambat</p>
        <p>Daftar peminjaman yang belum dikembalikan setelah jatuh tempo.</p>
    </div>

    @if($loans->count() > 0)
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
                            Keterlambatan
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loans as $loan)
                        @php
                            $daysOverdue = \Carbon\Carbon::today()->diffInDays($loan->due_date, false);
                        @endphp
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
                                <p class="text-red-600 font-bold">{{ $loan->due_date->format('d M Y') }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <span class="relative inline-block px-3 py-1 font-semibold text-red-900 leading-tight">
                                    <span aria-hidden="true" class="absolute inset-0 bg-red-200 opacity-50 rounded-full"></span>
                                    <span class="relative">{{ $daysOverdue }} hari</span>
                                </span>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <div class="flex space-x-2">
                                    <a href="{{ route('loans.show', $loan->id) }}" class="text-blue-600 hover:text-blue-900">
                                        Detail
                                    </a>
                                    <form action="{{ route('loans.return', $loan->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900">
                                            Kembalikan
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $loans->links() }}
        </div>
    @else
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-center">
            <p class="font-semibold">Tidak ada peminjaman terlambat!</p>
            <p>Semua buku telah dikembalikan tepat waktu.</p>
        </div>
    @endif
</div>
@endsection
