<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\ApplicantProfile;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@tramplin.local'],
            [
                'display_name' => 'Администратор',
                'password' => 'password',
                'role' => UserRole::Admin,
            ],
        );

        User::query()->updateOrCreate(
            ['email' => 'curator@tramplin.local'],
            [
                'display_name' => 'Куратор',
                'password' => 'password',
                'role' => UserRole::Curator,
            ],
        );

        User::query()->updateOrCreate(
            ['email' => 'student@tramplin.local'],
            [
                'display_name' => 'Тестовый студент',
                'password' => 'password',
                'role' => UserRole::Applicant,
            ],
        );


        $student = User::query()->updateOrCreate(
            ['email' => 'student@tramplin.local'],
            [
                'display_name' => 'Тестовый студент',
                'password' => 'password',
                'role' => UserRole::Applicant,
            ],
        );

        ApplicantProfile::query()->updateOrCreate(
            ['user_id' => $student->id],
            [
                'full_name' => 'Тестовый студент',
                'university_name' => 'Университет Технологий',
                'course' => 3,
                'graduation_year' => 2027,
                'about' => 'Студент направления программной инженерии.',
                'privacy_level' => 'platform_visible',
                'preferred_work_formats' => ['remote', 'hybrid'],
                'preferred_cities' => ['Москва', 'Санкт-Петербург'],
            ],
        );

        User::query()->updateOrCreate(
            ['email' => 'employer@tramplin.local'],
            [
                'display_name' => 'Тестовый работодатель',
                'password' => 'password',
                'role' => UserRole::Employer,
            ],
        );
    }
}
