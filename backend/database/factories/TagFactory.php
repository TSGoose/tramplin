<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Tag>
 */
final class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Vue',
            'React',
            'TypeScript',
            'PHP',
            'Laravel',
            'PostgreSQL',
            'UX',
            'Figma',
            'Remote',
            'Junior',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'group' => fake()->randomElement(['stack', 'format', 'level']),
        ];
    }
}
