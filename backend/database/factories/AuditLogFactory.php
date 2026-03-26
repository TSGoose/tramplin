<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AuditLog>
 */
final class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition(): array
    {
        return [
            'actor_user_id' => User::factory(),
            'entity_type' => fake()->randomElement(['company', 'opportunity']),
            'entity_id' => fake()->numberBetween(1, 100),
            'action' => fake()->randomElement(['approved', 'rejected', 'needs_revision']),
            'old_status' => fake()->randomElement(['draft', 'pending_verification']),
            'new_status' => fake()->randomElement(['verified', 'rejected', 'needs_revision']),
            'comment' => fake()->optional()->sentence(),
        ];
    }
}
