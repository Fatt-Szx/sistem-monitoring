<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\Pembimbing;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // 1) roles
        $adminRole     = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $mhsRole       = Role::firstOrCreate(['name' => 'mahasiswa', 'guard_name' => 'web']);
        $dosenRole     = Role::firstOrCreate(['name' => 'dosen', 'guard_name' => 'web']);

        // 2) admin
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            ['name' => 'Administrator', 'password' => bcrypt('unimus')]
        );
        $admin->syncRoles([$adminRole]);

        // 3) mahasiswa → username = NIM, pass = 'mahasiswa'
        Mahasiswa::with('user')->get()->each(function($m) use ($mhsRole) {
            $u = $m->user ?: User::create([
                'name' => $m->nama,
                'username' => $m->nim,
                'password' => bcrypt('mahasiswa'),
            ]);
            // pastikan relasi user_id di mahasiswa terisi
            if (!$m->user_id) { $m->user_id = $u->id; $m->save(); }
            $u->syncRoles([$mhsRole]);
        });

        // 4) dosen → username = NIK, pass = 'dosen'
        Pembimbing::with('user')->get()->each(function($d) use ($dosenRole) {
            $u = $d->user ?: User::create([
                'name' => trim($d->gelar_depan.' '.$d->nama.' '.$d->gelar_belakang),
                'username' => $d->nik,
                'password' => bcrypt('dosen'),
            ]);
            if (!$d->user_id) { $d->user_id = $u->id; $d->save(); }
            $u->syncRoles([$dosenRole]);
        });
    }
}
