<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-red-700">
            Tolak Pengajuan Cuti
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-red-50 via-white to-orange-50 py-8">

        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-2xl p-8">

                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-bold text-lg text-gray-800 mb-2">Detail Pengajuan</h3>
                    <p class="text-sm text-gray-600">
                        <strong>Karyawan:</strong> {{ $leaveRequest->user->name }}<br>
                        <strong>Jenis Cuti:</strong> {{ $leaveRequest->leaveType->name }}<br>
                        <strong>Tanggal:</strong> {{ $leaveRequest->start_date->format('d M Y') }} s/d {{ $leaveRequest->end_date->format('d M Y') }}<br>
                        <strong>Alasan:</strong> {{ $leaveRequest->reason }}
                    </p>
                </div>

                <form action="{{ route('admin.leave-requests.reject', $leaveRequest) }}" method="POST">

                    @csrf

                    <div class="mb-6">
                        <label class="block font-medium text-sm text-gray-700 mb-2">
                            Alasan Penolakan <span class="text-red-500">*</span>
                        </label>

                        <textarea
                            name="reject_reason"
                            rows="5"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                            placeholder="Masukkan alasan penolakan..."
                            required>{{ old('reject_reason') }}</textarea>

                        @error('reject_reason')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-2 justify-end">

                        <a href="{{ route('admin.leave-requests.index') }}"
                           class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            Batal
                        </a>

                        <button
                            type="submit"
                            onclick="return confirm('Yakin ingin menolak pengajuan ini?')"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Tolak Pengajuan
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>
