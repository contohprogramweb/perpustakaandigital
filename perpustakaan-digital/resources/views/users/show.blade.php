@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('users.index') }}" class="text-blue-600 hover:text-blue-900">&larr; Kembali ke Daftar Pengguna</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- User Info Card -->
        <div class="bg-white shadow-md rounded-lg p-6 md:col-span-1">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Pengguna</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500">Nama</p>
                    <p class="font-semibold">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-semibold">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Role</p>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $user->role === 'admin' ? 'bg-purple-200 text-purple-900' : 'bg-blue-200 text-blue-900' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                @if($user->phone)
                <div>
                    <p class="text-sm text-gray-500">Telepon</p>
                    <p class="font-semibold">{{ $user->phone }}</p>
                </div>
                @endif
                @if($user->address)
                <div>
                    <p class="text-sm text-gray-500">Alamat</p>
                    <p class="font-semibold">{{ $user->address }}</p>
                </div>
                @endif
                <div>
                    <p class="text-sm text-gray-500">Terdaftar Sejak</p>
                    <p class="font-semibold">{{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>
            <div class="mt-6 flex space-x-2">
                <a href="{{ route('users.edit', $user->id) }}" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white text-center py-2 px-4 rounded-md transition duration-200">
                    Edit
                </a>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md transition duration-200">
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        <!-- Active Loans Card -->
        <div class="bg-white shadow-md rounded-lg p-6 md:col-span-2">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Peminjaman Aktif ({{ $activeLoans->count() }})</h2>
            @if($activeLoans->count() > 0)
                <div class="space-y-4">
                    @foreach($activeLoans as $loan)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-semibold text-lg">{{ $loan->book->title }}</h3>
                                    <p class="text-sm text-gray-600">Oleh: {{ $loan->book->author }}</p>
                                    <p class="text-sm text-gray-600 mt-2">
                                        <span class="font-medium">Tanggal Pinjam:</span> {{ $loan->loan_date->format('d M Y') }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Jatuh Tempo:</span> 
                                        <span class="{{ $loan->isOverdue() ? 'text-red-600 font-bold' : '' }}">
                                            {{ $loan->due_date->format('d M Y') }}
                                            @if($loan->isOverdue())
                                                (Terlambat)
                                            @endif
                                        </span>
                                    </p>
                                </div>
                                <form action="{{ route('loans.return', $loan->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-md text-sm transition duration-200">
                                        Kembalikan
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Tidak ada peminjaman aktif.</p>
            @endif
        </div>
    </div>

    <!-- Loan History -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Riwayat Peminjaman</h2>
        @if($historyLoans->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Buku
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tanggal Pinjam
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tanggal Kembali
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($historyLoans as $loan)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="font-semibold">{{ $loan->book->title }}</p>
                                <p class="text-gray-600 text-xs">{{ $loan->book->author }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{ $loan->loan_date->format('d M Y') }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{ $loan->return_date ? $loan->return_date->format('d M Y') : '-' }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $loan->status === 'returned' ? 'bg-green-200 text-green-900' : 'bg-yellow-200 text-yellow-900' }}">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-8">Belum ada riwayat peminjaman.</p>
        @endif
    </div>
</div>
@endsection
