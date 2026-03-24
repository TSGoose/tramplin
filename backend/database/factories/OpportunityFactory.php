<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\OpportunityEmploymentType;
use App\Enums\OpportunityLevel;
use App\Enums\OpportunityStatus;
use App\Enums\OpportunityType;
use App\Enums\OpportunityWorkFormat;
use App\Models\Company;
use App\Models\Opportunity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Opportunity>
 */
final class OpportunityFactory extends Factory
{
    protected $model = Opportunity::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'title' => fake()->randomElement([
                'Junior Frontend Developer',
                'Стажер Laravel',
                'Ментор по веб-разработке',
                'Карьерный митап по frontend',
            ]),
            'short_description' => fake()->sentence(),
            'full_description' => fake()->paragraphs(3, true),
            'type' => fake()->randomElement(OpportunityType::cases()),
            'work_format' => fake()->randomElement(OpportunityWorkFormat::cases()),
            'employment_type' => fake()->randomElement(OpportunityEmploymentType::cases()),
            'level' => fake()->randomElement(OpportunityLevel::cases()),
            'city' => fake()->randomElement(['Москва', 'Санкт-Петербург', 'Казань']),
            'address' => fake()->streetAddress(),
            'latitude' => fake()->randomFloat(7, 55.60, 59.95),
            'longitude' => fake()->randomFloat(7, 30.20, 49.20),
            'is_remote' => fake()->boolean(),
            'published_at' => now()->subDays(fake()->numberBetween(1, 10)),
            'expires_at' => now()->addDays(fake()->numberBetween(10, 60)),
            'event_date' => fake()->optional()->dateTimeBetween('+3 days', '+45 days'),
            'salary_from' => fake()->optional()->numberBetween(40000, 120000),
            'salary_to' => fake()->optional()->numberBetween(120000, 220000),
            'contacts_text' => 'careers@example.com',
            'external_url' => fake()->optional()->url(),
            'status' => OpportunityStatus::Published,
            'moderation_comment' => null,
        ];
    }
}
