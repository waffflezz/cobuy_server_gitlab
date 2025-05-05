<?php

namespace Database\Seeders;

use App\Models\UserModel;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // UserModel::factory(10)->create();

        $this->call(GroupSeeder::class);
    }
}
