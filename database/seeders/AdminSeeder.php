<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        // Pastikan role admin tersedia
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web']
        );

        // Buat user admin (username & email)
        $admin = User::firstOrCreate(
            ['username' => 'admin'], // kunci unik
            [
                'name'     => 'Administrator',
                'email'    => 'admin@example.com', // bisa kamu ganti
                'password' => bcrypt('unimus'),
            ]
        );

        // Assign role admin
        $admin->syncRoles([$adminRole]);

        $this->command->info('✅ User admin berhasil dibuat atau diperbarui.');
    }
}
