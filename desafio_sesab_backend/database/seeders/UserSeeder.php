<?php

namespace Database\Seeders;

use App\Models\Roles;
use App\Models\Users;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Users::factory()
            ->sequence(fn($sequence) => ['role_id' => Roles::all()->random()])->count(30)->create();
    }
}