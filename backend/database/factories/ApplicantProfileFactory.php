<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ProfileModerationStatus;
use App\Enums\ProfilePrivacyLevel;
use App\Models\ApplicantProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ApplicantProfile>
 */
final class ApplicantProfileFactory extends Factory
{
    protected $model = ApplicantProfile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'full_name' => fake()->name(),
            'university_name' => 'Университет Технологий',
            'course' => fake()->numberBetween(1, 4),
            'graduation_year' => (int) date('Y') + fake()->numberBetween(0, 3),
            'about' => fake()->paragraph(),
            'resume_file_path' => null,
            'portfolio_url' => fake()->optional()->url(),
            'github_url' => fake()->optional()->url(),
            'privacy_level' => ProfilePrivacyLevel::PlatformVisible,
            'preferred_work_formats' => ['remote', 'hybrid'],
            'preferred_cities' => ['Москва', 'Санкт-Петербург'],
            'profile_views_count' => 0,
            'moderation_status' => ProfileModerationStatus::Unreviewed,
            'moderation_comment' => null,
        ];
    }
}
