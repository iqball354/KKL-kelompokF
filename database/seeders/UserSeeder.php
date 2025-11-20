<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Dosen Pengajar',
                'email' => 'dosen@gmail.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Ketua Prodi',
                'email' => 'kaprodi@gmail.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Dekan Fakultas',
                'email' => 'dekan@gmail.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Wakil Rektor 1',
                'email' => 'warek1@gmail.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Admin Akademik',
                'email' => 'akademik@gmail.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'HRD Office',
                'email' => 'hrd@gmail.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Staff SDM',
                'email' => 'sdm@gmail.com',
                'password' => bcrypt('password'),
            ],
        ]);
    }
}
