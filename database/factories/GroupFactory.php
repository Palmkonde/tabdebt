<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
 */
class GroupFactory extends Factory
{
    private static array $groups = [
        ['name' => 'Dev Tools', 'description' => 'Development tools and utilities'],
        ['name' => 'Learning', 'description' => 'Tutorials and learning resources'],
        ['name' => 'Design', 'description' => 'Design inspiration and tools'],
        ['name' => 'Social Media', 'description' => 'Social platforms and communities'],
        ['name' => 'Cloud Services', 'description' => 'Hosting and cloud providers'],
        ['name' => 'Documentation', 'description' => 'Official docs and references'],
        ['name' => 'Productivity', 'description' => 'Productivity and project management'],
        ['name' => 'News', 'description' => 'Tech news and blogs'],
        ['name' => 'Frontend', 'description' => 'Frontend frameworks and libraries'],
        ['name' => 'Backend', 'description' => 'Backend tools and frameworks'],
    ];

    private static int $index = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $group = self::$groups[self::$index % count(self::$groups)];
        self::$index++;

        return [
            'name' => $group['name'],
            'description' => $group['description'],
            'user_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}
