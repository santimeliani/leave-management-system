<x-app-layout>

<x-slot name="header">
    <div>
        <h2 class="font-bold text-2xl text-gray-800">
            Dashboard Karyawan
        </h2>
        <p class="text-sm text-gray-500">
            Sistem Pengajuan Cuti
        </p>
    </div>
</x-slot>

<div class="py-10 bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto px-6">

        <!-- Welcome -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-xl shadow-lg p-8 text-white">
            <h1 class="text-3xl font-bold">
                Selamat Datang, {{ auth()->user()->name }}
            </h1>
            <p class="mt-2 text-emerald-100">
                Ajukan dan pantau pengajuan cuti Anda di sini.
            </p>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">

            <div class="bg-white rounded-xl shadow p-6">
                <p class="text-gray-500">Total Pengajuan</p>
                <h3 class="text-3xl font-bold text-blue-600">{{ $stats['totalRequests'] }}</h3>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <p class="text-gray-500">Menunggu Persetujuan</p>
                <h3 class="text-3xl font-bold text-yellow-500">{{ $stats['pendingRequests'] }}</h3>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <p class="text-gray-500">Disetujui</p>
                <h3 class="text-3xl font-bold text-green-600">{{ $stats['approvedRequests'] }}</h3>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <p class="text-gray-500">Ditolak</p>
                <h3 class="text-3xl font-bold text-red-600">{{ $stats['rejectedRequests'] }}</h3>
            </div>

        </div>

        <!-- Menu Karyawan -->
        <div class="bg-white rounded-xl shadow mt-8 p-6">

            <h2 class="text-xl font-bold mb-5">Menu Cepat</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <a href="{{ route('leave-requests.create') }}"
                   class="border rounded-xl p-5 hover:bg-emerald-50 transition">
                    <h3 class="font-bold text-lg text-emerald-700">Ajukan Cuti</h3>
                    <p class="text-gray-500 mt-2">Buat pengajuan cuti baru.</p>
                </a>

                <a href="{{ route('leave-requests.index') }}"
                   class="border rounded-xl p-5 hover:bg-blue-50 transition">
                    <h3 class="font-bold text-lg text-blue-700">Daftar Pengajuan</h3>
                    <p class="text-gray-500 mt-2">Lihat semua pengajuan cuti Anda.</p>
                </a>

            </div>

        </div>

        <!-- Sisa Kuota Cuti -->
        @if(isset($leaveBalances) && $leaveBalances->isNotEmpty())
            <div class="bg-white rounded-xl shadow mt-8 p-6">
                <h2 class="text-xl font-bold mb-5">Sisa Kuota Cuti Tahun {{ date('Y') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    @foreach($leaveBalances as $balance)
                        <div class="border rounded-xl p-4 text-center
                            {{ $balance->remaining > 0 ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }}">
                            <p class="text-sm font-medium text-gray-600">{{ $balance->leaveType->name }}</p>
                            <p class="text-3xl font-bold mt-2
                                {{ $balance->remaining > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $balance->remaining }}
                            </p>
                            <p class="text-xs text-gray-400">hari tersisa</p>
                            <p class="text-xs text-gray-500 mt-1">
                                Terpakai: {{ $balance->used }}/{{ $balance->quota }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Pengajuan Terbaru -->
        @if(isset($recentRequests) && $recentRequests->isNotEmpty())
            <div class="bg-white rounded-xl shadow mt-8 p-6">
                <h2 class="text-xl font-bold mb-5">Pengajuan Terbaru</h2>
                <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-teal-600 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left">Jenis Cuti</th>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentRequests as $req)
                            <tr class="hover:bg-gray-50">
                                <td class="border px-4 py-3">{{ $req->leaveType->name }}</td>
                                <td class="border px-4 py-3">
                                    {{ $req->start_date->format('d M Y') }}
                                    <span class="text-gray-400">s/d</span>
                                    {{ $req->end_date->format('d M Y') }}
                                </td>
                                <td class="border px-4 py-3 text-center">
                                    @if($req->status === 'pending')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-sm">Pending</span>
                                    @elseif($req->status === 'approved')
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-sm">Disetujui</span>
                                    @elseif($req->status === 'rejected')
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-sm">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>

</div>

</x-app-layout>
