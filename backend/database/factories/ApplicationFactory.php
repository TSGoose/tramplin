<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ApplicationStatus;
use App\Models\ApplicantProfile;
use App\Models\Application;
use App\Models\Opportunity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Application>
 */
final class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition(): array
    {
        return [
            'opportunity_id' => Opportunity::factory(),
            'applicant_profile_id' => ApplicantProfile::factory(),
            'cover_letter' => fake()->optional()->paragraph(),
            'status' => ApplicationStatus::New,
            'employer_comment' => null,
        ];
    }
}
