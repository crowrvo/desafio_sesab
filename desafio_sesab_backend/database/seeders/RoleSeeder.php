<?php

namespace Database\Seeders;

use App\Models\Roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Roles::factory()->createMany(
            [['name' => 'Adm'], ['name' => 'Mod'], ['name' => 'user']]
        );
    }
}