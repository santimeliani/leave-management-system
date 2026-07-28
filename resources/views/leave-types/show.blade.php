<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Jenis Cuti
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-lg p-6">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-500 mb-1">
                        Nama Jenis Cuti
                    </label>
                    <p class="text-lg font-semibold text-gray-800">
                        {{ $leaveType->name }}
                    </p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-500 mb-1">
                        Deskripsi
                    </label>
                    <p class="text-gray-700">
                        {{ $leaveType->description ?? '-' }}
                    </p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-500 mb-1">
                        Jumlah Pengajuan
                    </label>
                    <p class="text-gray-700">
                        {{ $leaveType->leaveRequests()->count() }} pengajuan
                    </p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-500 mb-1">
                        Dibuat Pada
                    </label>
                    <p class="text-gray-700">
                        {{ $leaveType->created_at->format('d M Y H:i') }}
                    </p>
                </div>

                <div class="flex gap-2 mt-6">

                    <a href="{{ route('leave-types.edit', $leaveType) }}"
                       class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">

                        Edit

                    </a>

                    <a href="{{ route('leave-types.index') }}"
                       class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">

                        Kembali

                    </a>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
