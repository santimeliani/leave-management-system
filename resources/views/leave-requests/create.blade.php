<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajukan Cuti') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-lg p-6">

                {{-- Leave Balance Summary --}}
                @if(isset($leaveBalances) && $leaveBalances->isNotEmpty())
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h3 class="font-semibold text-blue-800 mb-3">Sisa Kuota Cuti Tahun {{ date('Y') }}</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($leaveTypes as $leaveType)
                                @php
                                    $balance = $leaveBalances->get($leaveType->id);
                                @endphp
                                <div class="bg-white rounded-lg p-3 border">
                                    <p class="text-sm font-medium text-gray-600">{{ $leaveType->name }}</p>
                                    <p class="text-lg font-bold {{ $balance && $balance->remaining > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $balance ? $balance->remaining : '-' }} hari
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        Terpakai: {{ $balance ? $balance->used : 0 }} / {{ $balance ? $balance->quota : '-' }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form action="{{ route('leave-requests.store') }}" method="POST" enctype="multipart/form-data">

                    @csrf

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
                                @php
                                    $balance = $leaveBalances->get($leaveType->id);
                                    $remaining = $balance ? $balance->remaining : '-';
                                @endphp
                                <option value="{{ $leaveType->id }}"
                                    {{ old('leave_type_id') == $leaveType->id ? 'selected' : '' }}>
                                    {{ $leaveType->name }} (Sisa: {{ $remaining }} hari)
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
                            value="{{ old('start_date') }}"
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
                            value="{{ old('end_date') }}"
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
                            required>{{ old('reason') }}</textarea>

                        @error('reason')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block font-medium text-sm text-gray-700">
                            Lampiran <span class="text-gray-400 text-xs">(opsional - PDF, DOC, DOCX, JPG, PNG. Maks 100MB)</span>
                        </label>

                        <input
                            type="file"
                            name="attachment"
                            class="w-full mt-1 rounded-md border-gray-300 shadow-sm"
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">

                        @error('attachment')
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
                            Ajukan Cuti
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>
