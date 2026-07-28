<x-app-layout>

    <x-slot name="header">
        <h2 class="text-2xl font-bold text-blue-700">
            📋 Daftar Pengajuan Cuti Karyawan
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-100 via-white to-indigo-100 py-8">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-2xl p-8">

                @if(session('success'))
                    <div class="bg-green-100 border border-green-300 text-green-700 p-4 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">

                    <thead class="bg-blue-600 text-white">

                        <tr>

                            <th class="px-4 py-3">Karyawan</th>

                            <th class="px-4 py-3">Jenis Cuti</th>

                            <th class="px-4 py-3">Tanggal</th>

                            <th class="px-4 py-3">Alasan</th>

                            <th class="px-4 py-3">Lampiran</th>

                            <th class="px-4 py-3">Status</th>

                            <th class="px-4 py-3">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                    @foreach($leaveRequests as $request)

                        <tr class="hover:bg-blue-50 transition duration-200">

                            <td class="border px-4 py-3">
                                {{ $request->user->name }}
                            </td>

                            <td class="border px-4 py-3">
                                {{ $request->leaveType->name }}
                            </td>

                            <td class="border px-4 py-3">
                                {{ $request->start_date }}
                                <br>
                                <span class="text-gray-500">s/d</span>
                                <br>
                                {{ $request->end_date }}
                            </td>

                            <td class="border px-4 py-3">
                                {{ $request->reason }}
                            </td>

                            <td class="border px-4 py-3 text-center">
                                @if($request->attachment)
                                    <a href="{{ route('leave-requests.attachment', $request) }}"
                                       class="text-blue-600 hover:underline">
                                        📎
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            <td class="border px-4 py-3">

                                @if(strtolower($request->status) == 'pending')

                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-semibold">
                                        Pending
                                    </span>

                                @elseif(strtolower($request->status) == 'approved')

                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                                        Approved
                                    </span>

                                @elseif(strtolower($request->status) == 'rejected')

                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">
                                        Rejected
                                    </span>

                                @endif

                            </td>

                            <td class="border px-4 py-3">

                                @if(strtolower($request->status) == 'pending')

                                    <form action="{{ route('admin.leave-requests.approve', $request->id) }}"
                                          method="POST"
                                          class="inline">

                                        @csrf

                                        <button
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow">
                                            ✔ Approve
                                        </button>

                                    </form>

                                    <a href="{{ route('admin.leave-requests.reject.form', $request->id) }}"
                                       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow inline-block ml-2">
                                        ✖ Reject
                                    </a>

                                @else

                                    <span class="text-gray-400">Selesai</span>

                                @endif

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-app-layout>