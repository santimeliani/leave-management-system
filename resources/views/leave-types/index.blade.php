<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Master Jenis Cuti') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Pesan sukses --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-md rounded-lg p-6">

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">
                        Daftar Jenis Cuti
                    </h3>

                    <a href="{{ route('leave-types.create') }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        + Tambah Jenis Cuti
                    </a>
                </div>

                <table class="min-w-full border border-gray-300">

                    <thead class="bg-gray-200">

                        <tr>

                            <th class="border px-4 py-2 text-center">
                                No
                            </th>

                            <th class="border px-4 py-2">
                                Nama Jenis Cuti
                            </th>

                            <th class="border px-4 py-2">
                                Deskripsi
                            </th>

                            <th class="border px-4 py-2 text-center">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($leaveTypes as $leaveType)

                        <tr>

                            <td class="border px-4 py-2 text-center">
                                {{ $loop->iteration }}
                            </td>

                            <td class="border px-4 py-2">
                                {{ $leaveType->name }}
                            </td>

                            <td class="border px-4 py-2">
                                {{ $leaveType->description }}
                            </td>

                            <td class="border px-4 py-2 text-center">

                                <a href="{{ route('leave-types.edit',$leaveType->id) }}"
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">
                                    Edit
                                </a>

                                <form action="{{ route('leave-types.destroy',$leaveType->id) }}"
                                      method="POST"
                                      class="inline">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        onclick="return confirm('Yakin ingin menghapus data ini?')"
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">

                                        Hapus

                                    </button>

                                </form>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="4" class="text-center py-4">

                                Belum ada data jenis cuti.

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>
</x-app-layout>