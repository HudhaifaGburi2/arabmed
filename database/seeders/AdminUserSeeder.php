<?php

namespace Database\Seeders;

use App\Enums\UserStatus;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@example.com');
        $password = env('ADMIN_PASSWORD', 'password');

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'uuid' => (string) Str::uuid(),
                'first_name' => 'Admin',
                'last_name' => 'User',
                'phone' => null,
                'email_verified_at' => now(),
                'password' => Hash::make($password),
                'status' => UserStatus::Active->value,
            ]
        );

        if ($role = Role::where('name', 'admin')->first()) {
            // attach if not attached
            if (! $user->roles()->where('roles.id', $role->id)->exists()) {
                $user->roles()->attach($role->id, ['assigned_at' => now()]);
            }
        }
    }
}
