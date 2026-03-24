<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
final class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'owner_user_id' => User::factory()->state([
                'role' => UserRole::Employer,
            ]),
            'name' => fake()->company(),
            'description' => fake()->paragraph(),
            'industry' => fake()->randomElement(['IT', 'Fintech', 'EdTech']),
            'website_url' => fake()->url(),
            'social_url' => fake()->url(),
            'inn' => (string) fake()->numberBetween(1000000000, 9999999999),
            'city' => fake()->randomElement(['Москва', 'Санкт-Петербург', 'Казань']),
            'address' => fake()->streetAddress(),
            'verification_status' => 'verified',
            'verification_comment' => null,
            'verified_at' => now(),
        ];
    }
}
