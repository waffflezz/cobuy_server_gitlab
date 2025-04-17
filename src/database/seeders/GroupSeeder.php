<?php

namespace Database\Seeders;

use App\Models\GroupModel;
use App\Models\UserModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = UserModel::first();

        if (!$user) {
            $this->command->info('Тестовый пользователь не найден. Пожалуйста, создайте пользователя перед запуском сидера.');
            return;
        }

        $group1 = GroupModel::create([
            'name' => 'Test GroupModel 1',
            'image' => null,
            'owner_id' => $user->id,
        ]);
        $group1->users()->attach($user);


        $group2 = GroupModel::create([
            'name' => 'Test GroupModel 2',
            'image' => null,
            'owner_id' => $user->id,
        ]);
        $group2->users()->attach($user);
    }
}
