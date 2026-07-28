<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Jenis Cuti
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-lg p-6">

                <form action="{{ route('leave-types.update', $leaveType) }}" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium mb-2">
                            Nama Jenis Cuti
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $leaveType->name) }}"
                            class="w-full border rounded px-3 py-2">

                        @error('name')
                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">
                            Deskripsi
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            class="w-full border rounded px-3 py-2">{{ old('description', $leaveType->description) }}</textarea>

                        @error('description')
                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex gap-2">

                        <button
                            type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">

                            Simpan

                        </button>

                        <a href="{{ route('leave-types.index') }}"
                           class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">

                            Kembali

                        </a>

                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>
