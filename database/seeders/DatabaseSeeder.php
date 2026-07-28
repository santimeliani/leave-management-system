<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\LeaveType;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Admin user
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@cuti.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Karyawan users
        $karyawan1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@cuti.com',
            'password' => Hash::make('password'),
            'role' => 'karyawan',
            'email_verified_at' => now(),
        ]);

        $karyawan2 = User::create([
            'name' => 'Siti Rahayu',
            'email' => 'siti@cuti.com',
            'password' => Hash::make('password'),
            'role' => 'karyawan',
            'email_verified_at' => now(),
        ]);

        $karyawan3 = User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad@cuti.com',
            'password' => Hash::make('password'),
            'role' => 'karyawan',
            'email_verified_at' => now(),
        ]);

        // Leave Types
        $cutiTahunan = LeaveType::create([
            'name' => 'Cuti Tahunan',
            'description' => 'Cuti tahunan yang diberikan kepada karyawan setiap tahunnya.',
        ]);

        $cutiSakit = LeaveType::create([
            'name' => 'Cuti Sakit',
            'description' => 'Cuti untuk karyawan yang sedang sakit dengan surat keterangan dokter.',
        ]);

        $cutiMelahirkan = LeaveType::create([
            'name' => 'Cuti Melahirkan',
            'description' => 'Cuti untuk karyawan yang melahirkan.',
        ]);

        $cutiMenikah = LeaveType::create([
            'name' => 'Cuti Menikah',
            'description' => 'Cuti untuk karyawan yang sedang menikah.',
        ]);

        $cutiDinas = LeaveType::create([
            'name' => 'Cuti Dinas Luar',
            'description' => 'Cuti untuk keperluan dinas luar kota/negara.',
        ]);

        // Leave Balances for each karyawan
        $year = (int) date('Y');
        $karyawans = [$karyawan1, $karyawan2, $karyawan3];
        $leaveTypes = [$cutiTahunan, $cutiSakit, $cutiMelahirkan, $cutiMenikah, $cutiDinas];

        foreach ($karyawans as $karyawan) {
            foreach ($leaveTypes as $leaveType) {
                LeaveBalance::create([
                    'user_id' => $karyawan->id,
                    'leave_type_id' => $leaveType->id,
                    'quota' => $leaveType->id === $cutiTahunan->id ? 12 : 5,
                    'used' => 0,
                    'year' => $year,
                ]);
            }
        }

        // Sample Leave Requests
        LeaveRequest::create([
            'user_id' => $karyawan1->id,
            'leave_type_id' => $cutiTahunan->id,
            'start_date' => '2026-08-01',
            'end_date' => '2026-08-03',
            'reason' => 'Liburan keluarga ke Bali.',
            'status' => 'pending',
        ]);

        LeaveRequest::create([
            'user_id' => $karyawan2->id,
            'leave_type_id' => $cutiSakit->id,
            'start_date' => '2026-07-28',
            'end_date' => '2026-07-29',
            'reason' => 'Sakit demam, perlu istirahat di rumah.',
            'status' => 'approved',
            'decision_at' => now()->subDay(),
        ]);

        // Update used balance for approved request
        $balance = LeaveBalance::where('user_id', $karyawan2->id)
            ->where('leave_type_id', $cutiSakit->id)
            ->where('year', $year)
            ->first();
        if ($balance) {
            $balance->update(['used' => 2]);
        }

        LeaveRequest::create([
            'user_id' => $karyawan3->id,
            'leave_type_id' => $cutiDinas->id,
            'start_date' => '2026-08-10',
            'end_date' => '2026-08-12',
            'reason' => 'Dinas luar ke Surabaya untuk meeting klien.',
            'status' => 'pending',
        ]);

        LeaveRequest::create([
            'user_id' => $karyawan1->id,
            'leave_type_id' => $cutiTahunan->id,
            'start_date' => '2026-07-20',
            'end_date' => '2026-07-21',
            'reason' => 'Keperluan keluarga.',
            'status' => 'rejected',
            'reject_reason' => 'Masalah operasional, mohon ditunda.',
            'decision_at' => now()->subDays(3),
        ]);
    }
}
