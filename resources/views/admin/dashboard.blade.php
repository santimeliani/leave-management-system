<x-app-layout>

<x-slot name="header">

    <div>
        <h2 class="font-bold text-2xl text-gray-800">
            Dashboard Admin
        </h2>

        <p class="text-sm text-gray-500">
            Sistem Pengajuan Cuti Karyawan
        </p>
    </div>

</x-slot>


<div class="py-10 bg-gray-100 min-h-screen">


<div class="max-w-7xl mx-auto px-6">


    <!-- Welcome -->

   <div class="bg-gradient-to-r from-slate-700 to-blue-800 
rounded-xl shadow-lg p-8 text-white">

        <h1 class="text-3xl font-bold">
            Selamat Datang, {{ auth()->user()->name }}
        </h1>
      
<p class="mt-2 text-slate-200">
    Kelola administrasi cuti karyawan secara efektif dan terstruktur.
</p>

    </div>


    <!-- Statistik -->

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">


        <!-- Total Karyawan -->

        <div class="bg-white rounded-xl shadow p-6">

            <p class="text-gray-500">
                Total Karyawan
            </p>

            <h3 class="text-3xl font-bold text-blue-600">
                {{ $stats['totalEmployees'] }}
            </h3>

        </div>




        <!-- Total Pengajuan -->

        <div class="bg-white rounded-xl shadow p-6">

            <p class="text-gray-500">
                Total Pengajuan Cuti
            </p>

            <h3 class="text-3xl font-bold text-indigo-600">
                {{ $stats['totalRequests'] }}
            </h3>

        </div>




        <!-- Pending -->

        <div class="bg-white rounded-xl shadow p-6">

            <p class="text-gray-500">
                Menunggu Persetujuan
            </p>

            <h3 class="text-3xl font-bold text-yellow-500">
                {{ $stats['pendingRequests'] }}
            </h3>

        </div>




        <!-- Approved -->

        <div class="bg-white rounded-xl shadow p-6">

            <p class="text-gray-500">
                Cuti Disetujui
            </p>

            <h3 class="text-3xl font-bold text-green-600">
                {{ $stats['approvedRequests'] }}
            </h3>

        </div>


    </div>


    <!-- Pengajuan Terbaru -->
    <div class="bg-white rounded-xl shadow mt-8 p-6">

        <h2 class="text-xl font-bold mb-5">
            Pengajuan Terbaru (Menunggu Persetujuan)
        </h2>

        @if($pendingRequests->isEmpty())
            <p class="text-gray-500 text-center py-4">
                Tidak ada pengajuan yang menunggu persetujuan.
            </p>
        @else
            <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-yellow-500 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left">Karyawan</th>
                        <th class="px-4 py-3 text-left">Jenis Cuti</th>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingRequests as $request)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-3">{{ $request->user->name }}</td>
                            <td class="border px-4 py-3">{{ $request->leaveType->name }}</td>
                            <td class="border px-4 py-3">
                                {{ $request->start_date->format('d M Y') }}
                                <span class="text-gray-400">s/d</span>
                                {{ $request->end_date->format('d M Y') }}
                            </td>
                            <td class="border px-4 py-3 text-center">
                                <a href="{{ route('admin.leave-requests.index') }}"
                                   class="text-blue-600 hover:underline text-sm font-semibold">
                                    Review
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    </div>


    <!-- Menu Admin -->

    <div class="bg-white rounded-xl shadow mt-8 p-6">


        <h2 class="text-xl font-bold mb-5">
            Menu Admin
        </h2>



        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">



            <a href="{{ route('leave-types.index') }}"
            class="border rounded-xl p-5 hover:bg-gray-50">


                <h3 class="font-bold text-lg">
                    Jenis Cuti
                </h3>


                <p class="text-gray-500 mt-2">
                    Kelola jenis cuti karyawan.
                </p>


            </a>




            <a href="{{ route('admin.leave-requests.index') }}"
            class="border rounded-xl p-5 hover:bg-gray-50">


                <h3 class="font-bold text-lg">
                    Approval Cuti
                </h3>


                <p class="text-gray-500 mt-2">
                    Periksa dan proses pengajuan cuti.
                </p>


            </a>


        </div>


    </div>



</div>


</div>


</x-app-layout>
