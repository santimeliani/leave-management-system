<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Pengajuan Cuti
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-lg p-6">

                <form action="{{ route('leave-requests.update', $leaveRequest) }}" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">
                            Jenis Cuti
                        </label>

                        <select
                            name="leave_type_id"
                            class="w-full mt-1 rounded-md border-gray-300 shadow-sm"
                            required>

                            <option value="">-- Pilih Jenis Cuti --</option>

                            @foreach($leaveTypes as $leaveType)
                                <option value="{{ $leaveType->id }}"
                                    {{ old('leave_type_id', $leaveRequest->leave_type_id) == $leaveType->id ? 'selected' : '' }}>
                                    {{ $leaveType->name }}
                                </option>
                            @endforeach

                        </select>

                        @error('leave_type_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">
                            Tanggal Mulai
                        </label>

                        <input
                            type="date"
                            name="start_date"
                            class="w-full mt-1 rounded-md border-gray-300 shadow-sm"
                            value="{{ old('start_date', $leaveRequest->start_date->format('Y-m-d')) }}"
                            required>

                        @error('start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">
                            Tanggal Selesai
                        </label>

                        <input
                            type="date"
                            name="end_date"
                            class="w-full mt-1 rounded-md border-gray-300 shadow-sm"
                            value="{{ old('end_date', $leaveRequest->end_date->format('Y-m-d')) }}"
                            required>

                        @error('end_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block font-medium text-sm text-gray-700">
                            Alasan Cuti
                        </label>

                        <textarea
                            name="reason"
                            rows="5"
                            class="w-full mt-1 rounded-md border-gray-300 shadow-sm"
                            required>{{ old('reason', $leaveRequest->reason) }}</textarea>

                        @error('reason')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">

                        <a href="{{ route('leave-requests.index') }}"
                           class="px-4 py-2 bg-gray-500 text-white rounded mr-2">
                            Kembali
                        </a>

                        <button
                           type="submit"
                           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Simpan Perubahan
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>
