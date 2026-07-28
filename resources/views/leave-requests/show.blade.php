<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Pengajuan Cuti
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-lg p-6">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-500 mb-1">
                        Jenis Cuti
                    </label>
                    <p class="text-lg font-semibold text-gray-800">
                        {{ $leaveRequest->leaveType->name }}
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">
                            Tanggal Mulai
                        </label>
                        <p class="text-gray-700">
                            {{ $leaveRequest->start_date->format('d M Y') }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">
                            Tanggal Selesai
                        </label>
                        <p class="text-gray-700">
                            {{ $leaveRequest->end_date->format('d M Y') }}
                        </p>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-500 mb-1">
                        Durasi
                    </label>
                    <p class="text-gray-700">
                        {{ $leaveRequest->duration_in_days }} hari
                    </p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-500 mb-1">
                        Alasan
                    </label>
                    <p class="text-gray-700">
                        {{ $leaveRequest->reason }}
                    </p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-500 mb-1">
                        Status
                    </label>
                    @if($leaveRequest->status === 'pending')
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold">
                            Pending
                        </span>
                    @elseif($leaveRequest->status === 'approved')
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                            Disetujui
                        </span>
                    @elseif($leaveRequest->status === 'rejected')
                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">
                            Ditolak
                        </span>
                    @endif
                </div>

                @if($leaveRequest->status === 'rejected' && $leaveRequest->reject_reason)
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <label class="block text-sm font-medium text-red-700 mb-1">
                            Alasan Penolakan
                        </label>
                        <p class="text-red-600">
                            {{ $leaveRequest->reject_reason }}
                        </p>
                    </div>
                @endif

                @if($leaveRequest->decision_at)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-500 mb-1">
                            Diputuskan Pada
                        </label>
                        <p class="text-gray-700">
                            {{ $leaveRequest->decision_at->format('d M Y H:i') }}
                        </p>
                    </div>
                @endif

                <div class="flex gap-2 mt-6">

                    @if($leaveRequest->status === 'pending')
                        <a href="{{ route('leave-requests.edit', $leaveRequest) }}"
                           class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                            Edit
                        </a>

                        <form action="{{ route('leave-requests.destroy', $leaveRequest) }}"
                              method="POST"
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button
                                onclick="return confirm('Yakin ingin menghapus pengajuan ini?')"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                                Hapus
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('leave-requests.index') }}"
                       class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Kembali
                    </a>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
