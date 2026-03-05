<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    private static array $tags = [
        'php', 'javascript', 'css', 'html', 'laravel',
        'react', 'vue', 'api', 'database', 'design',
        'devops', 'frontend', 'backend', 'tutorial', 'docs',
        'open-source', 'hosting', 'ui', 'testing', 'security',
    ];

    private static int $index = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = self::$tags[self::$index % count(self::$tags)];
        self::$index++;

        return [
            'name' => $name,
            'color' => $this->faker->hexColor(),
        ];
    }
}
