<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['name' => 'admin1234'],
            [
                'email' => 'admin@wms.local',
                'password' => Hash::make('admin1234'),
            ]
        );

        $this->command->info('Admin user created successfully! Username: admin1234, Password: admin1234');
    }
}
