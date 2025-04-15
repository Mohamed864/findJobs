<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;


class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): \App\Models\User
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('12345678'),
        ]);

        return $user;
    }
}
