<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'display_name' => 'Administrator', 'description' => 'System administrator'],
            ['name' => 'teacher', 'display_name' => 'Teacher', 'description' => 'Course instructor'],
            ['name' => 'student', 'display_name' => 'Student', 'description' => 'Learner'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
