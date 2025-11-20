<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Dosen Pengajar',
                'email' => 'dosen@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Ketua Prodi',
                'email' => 'kaprodi@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Dekan Fakultas',
                'email' => 'dekan@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Wakil Rektor 1',
                'email' => 'warek1@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Admin Akademik',
                'email' => 'akademik@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'HRD Office',
                'email' => 'hrd@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Staff SDM',
                'email' => 'sdm@gmail.com',
                'password' => Hash::make('password'),
            ],
        ]);
    }
}
