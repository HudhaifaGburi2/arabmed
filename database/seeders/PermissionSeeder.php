<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Users
            ['name' => 'users.view', 'display_name' => 'View Users', 'group_name' => 'users'],
            ['name' => 'users.create', 'display_name' => 'Create Users', 'group_name' => 'users'],
            ['name' => 'users.update', 'display_name' => 'Update Users', 'group_name' => 'users'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'group_name' => 'users'],

            // Courses
            ['name' => 'courses.view', 'display_name' => 'View Courses', 'group_name' => 'courses'],
            ['name' => 'courses.create', 'display_name' => 'Create Courses', 'group_name' => 'courses'],
            ['name' => 'courses.update', 'display_name' => 'Update Courses', 'group_name' => 'courses'],
            ['name' => 'courses.delete', 'display_name' => 'Delete Courses', 'group_name' => 'courses'],
            ['name' => 'courses.publish', 'display_name' => 'Publish Courses', 'group_name' => 'courses'],

            // Videos
            ['name' => 'videos.view', 'display_name' => 'View Videos', 'group_name' => 'videos'],
            ['name' => 'videos.create', 'display_name' => 'Create Videos', 'group_name' => 'videos'],
            ['name' => 'videos.update', 'display_name' => 'Update Videos', 'group_name' => 'videos'],
            ['name' => 'videos.delete', 'display_name' => 'Delete Videos', 'group_name' => 'videos'],

            // Documents
            ['name' => 'documents.view', 'display_name' => 'View Documents', 'group_name' => 'documents'],
            ['name' => 'documents.create', 'display_name' => 'Create Documents', 'group_name' => 'documents'],
            ['name' => 'documents.update', 'display_name' => 'Update Documents', 'group_name' => 'documents'],
            ['name' => 'documents.delete', 'display_name' => 'Delete Documents', 'group_name' => 'documents'],

            // Exams
            ['name' => 'exams.view', 'display_name' => 'View Exams', 'group_name' => 'exams'],
            ['name' => 'exams.create', 'display_name' => 'Create Exams', 'group_name' => 'exams'],
            ['name' => 'exams.update', 'display_name' => 'Update Exams', 'group_name' => 'exams'],
            ['name' => 'exams.delete', 'display_name' => 'Delete Exams', 'group_name' => 'exams'],
            ['name' => 'exams.publish', 'display_name' => 'Publish Exams', 'group_name' => 'exams'],
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(
                ['name' => $perm['name']],
                [
                    'display_name' => $perm['display_name'],
                    'group_name' => $perm['group_name'],
                    'description' => $perm['display_name'],
                ]
            );
        }
    }
}
