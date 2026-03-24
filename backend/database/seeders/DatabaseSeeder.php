<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'display_name' => 'Администратор',
            'email' => 'admin@tramplin.local',
            'role' => UserRole::Admin,
        ]);

        User::factory()->create([
            'display_name' => 'Куратор',
            'email' => 'curator@tramplin.local',
            'role' => UserRole::Curator,
        ]);

        User::factory()->count(2)->role(UserRole::Employer)->create();
        User::factory()->count(3)->role(UserRole::Applicant)->create();
    }
}
