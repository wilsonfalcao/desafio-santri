<?php

namespace Database\Seeders;

use App\Enums\ClientTypeEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'santri',
            'email' => 'admin@santri.com.br',
            'uf' => 'GO',
            'is_premium' => 1,
            'client_type' => ClientTypeEnum::WHOLESALE->value,
            'password' => Hash::make('password'),
        ]);
    }
}
