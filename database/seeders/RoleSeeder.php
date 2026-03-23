<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'title' => 'Администратор'],
            ['name' => 'analyst', 'title' => 'Аналитик'],
            ['name' => 'leader', 'title' => 'Руководитель'],
            ['name' => 'manager', 'title' => 'Менеджер / Сотрудник'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], ['title' => $role['title']]);
        }
    }
}