<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::where('name', 'admin')->first();
        $teacher = Role::where('name', 'teacher')->first();
        $student = Role::where('name', 'student')->first();

        if ($admin) {
            $admin->permissions()->sync(Permission::pluck('id')->all());
        }

        if ($teacher) {
            $teacherPerms = Permission::whereIn('name', [
                'courses.view','courses.create','courses.update','courses.delete','courses.publish',
                'videos.view','videos.create','videos.update','videos.delete',
                'documents.view','documents.create','documents.update','documents.delete',
                'exams.view','exams.create','exams.update','exams.delete','exams.publish',
            ])->pluck('id')->all();
            $teacher->permissions()->sync($teacherPerms);
        }

        if ($student) {
            $studentPerms = Permission::whereIn('name', [
                'courses.view','videos.view','documents.view','exams.view',
            ])->pluck('id')->all();
            $student->permissions()->sync($studentPerms);
        }
    }
}
