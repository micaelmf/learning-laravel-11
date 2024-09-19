<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cria um usuário padrão
        User::create([
            'name' => 'micaelmf',
            'email' => 'micaelmf@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123123123'), // Senha padrão
            'remember_token' => Str::random(10),
        ]);

        User::factory()
            ->count(5)
            ->create();
    }
}
