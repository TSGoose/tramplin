<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\ApplicantProfile;
use App\Models\Company;
use App\Models\Opportunity;
use App\Models\Tag;

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

        $tags = collect([
            ['name' => 'Vue', 'slug' => 'vue', 'group' => 'stack'],
            ['name' => 'Laravel', 'slug' => 'laravel', 'group' => 'stack'],
            ['name' => 'TypeScript', 'slug' => 'typescript', 'group' => 'stack'],
            ['name' => 'Remote', 'slug' => 'remote', 'group' => 'format'],
            ['name' => 'Junior', 'slug' => 'junior', 'group' => 'level'],
            ['name' => 'Internship', 'slug' => 'internship', 'group' => 'type'],
        ])->map(static fn (array $tag): Tag => Tag::query()->updateOrCreate(
            ['slug' => $tag['slug']],
            $tag,
        ));

        $employerUser = User::query()->where('email', 'employer@tramplin.local')->first();

        if ($employerUser !== null) {
            $company = Company::query()->updateOrCreate(
                ['owner_user_id' => $employerUser->id],
                [
                    'name' => 'Digital Horizon',
                    'description' => 'Технологическая компания-партнер.',
                    'industry' => 'IT',
                    'website_url' => 'https://digital-horizon.example',
                    'social_url' => 'https://t.me/digital_horizon',
                    'inn' => '7701234567',
                    'city' => 'Москва',
                    'address' => 'ул. Тверская, 7',
                    'verification_status' => 'verified',
                    'verified_at' => now(),
                ],
            );

            if (Opportunity::query()->count() < 20) {
                Opportunity::factory()
                    ->count(20)
                    ->for($company)
                    ->create()
                    ->each(static function (Opportunity $opportunity) use ($tags): void {
                        $opportunity->tags()->sync(
                            $tags->random(random_int(2, 4))->pluck('id')->all()
                        );
                    });
            }
        }


    }
}
