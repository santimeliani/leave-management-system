<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengajuan Cuti') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-lg">

                <div class="flex justify-between items-center p-6 border-b">
                    <h3 class="text-lg font-semibold">
                        Daftar Pengajuan Cuti
                    </h3>

                    <a href="{{ route('leave-requests.create') }}"
                       class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        + Ajukan Cuti
                    </a>
                </div>

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left">No</th>
                            <th class="px-6 py-3 text-left">Jenis Cuti</th>
                            <th class="px-6 py-3 text-left">Tanggal Mulai</th>
                            <th class="px-6 py-3 text-left">Tanggal Selesai</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">

                        @forelse($leaveRequests as $request)

                            <tr>

                                <td class="px-6 py-4">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $request->leaveType->name }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $request->start_date->format('d M Y') }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $request->end_date->format('d M Y') }}
                                </td>

                                <td class="px-6 py-4">

                                   @if(strtolower($request->status) == 'pending')

    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded">
        pending
    </span>

@elseif(strtolower($request->status) == 'approved')

    <span class="px-2 py-1 bg-green-100 text-green-700 rounded">
        Approved
    </span>

@elseif(strtolower($request->status) == 'rejected')

    <span class="px-2 py-1 bg-red-100 text-red-700 rounded">
        Rejected
    </span>

@endif

                                </td>

                                <td class="px-6 py-4 text-center">

                                    <a href="{{ route('leave-requests.show', $request) }}"
                                       class="text-blue-600 hover:underline">
                                        Detail
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center py-6 text-gray-500">
                                    Belum ada pengajuan cuti.
                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>