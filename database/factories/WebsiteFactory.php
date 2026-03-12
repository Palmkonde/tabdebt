<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Website>
 */
class WebsiteFactory extends Factory
{
    private static array $websites = [
        ['name' => 'GitHub', 'url' => 'https://github.com', 'description' => 'Code hosting and collaboration platform'],
        ['name' => 'Laravel', 'url' => 'https://laravel.com', 'description' => 'PHP web application framework'],
        ['name' => 'Tailwind CSS', 'url' => 'https://tailwindcss.com', 'description' => 'Utility-first CSS framework'],
        ['name' => 'Stack Overflow', 'url' => 'https://stackoverflow.com', 'description' => 'Q&A for developers'],
        ['name' => 'MDN Web Docs', 'url' => 'https://developer.mozilla.org', 'description' => 'Web development documentation'],
        ['name' => 'YouTube', 'url' => 'https://youtube.com', 'description' => 'Video sharing platform'],
        ['name' => 'Reddit', 'url' => 'https://reddit.com', 'description' => 'Community discussion forum'],
        ['name' => 'Figma', 'url' => 'https://figma.com', 'description' => 'Collaborative design tool'],
        ['name' => 'Notion', 'url' => 'https://notion.so', 'description' => 'All-in-one workspace for notes and docs'],
        ['name' => 'Vercel', 'url' => 'https://vercel.com', 'description' => 'Frontend deployment platform'],
        ['name' => 'DigitalOcean', 'url' => 'https://digitalocean.com', 'description' => 'Cloud infrastructure provider'],
        ['name' => 'CSS Tricks', 'url' => 'https://css-tricks.com', 'description' => 'Tips and tricks for CSS'],
        ['name' => 'Dev.to', 'url' => 'https://dev.to', 'description' => 'Developer community blog'],
        ['name' => 'Dribbble', 'url' => 'https://dribbble.com', 'description' => 'Design inspiration showcase'],
        ['name' => 'Hacker News', 'url' => 'https://news.ycombinator.com', 'description' => 'Tech news aggregator'],
        ['name' => 'PHP.net', 'url' => 'https://php.net', 'description' => 'Official PHP documentation'],
        ['name' => 'NPM', 'url' => 'https://npmjs.com', 'description' => 'JavaScript package registry'],
        ['name' => 'Docker Hub', 'url' => 'https://hub.docker.com', 'description' => 'Container image registry'],
        ['name' => 'Postman', 'url' => 'https://postman.com', 'description' => 'API development and testing tool'],
        ['name' => 'Google Fonts', 'url' => 'https://fonts.google.com', 'description' => 'Free web font library'],
        ['name' => 'Unsplash', 'url' => 'https://unsplash.com', 'description' => 'Free high-resolution photos'],
        ['name' => 'Canva', 'url' => 'https://canva.com', 'description' => 'Online graphic design tool'],
        ['name' => 'Trello', 'url' => 'https://trello.com', 'description' => 'Project management board'],
        ['name' => 'Cloudflare', 'url' => 'https://cloudflare.com', 'description' => 'CDN and security platform'],
        ['name' => 'Heroku', 'url' => 'https://heroku.com', 'description' => 'Cloud application platform'],
    ];

    private static int $index = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $site = self::$websites[self::$index % count(self::$websites)];
        self::$index++;

        return [
            'name' => $site['name'],
            'url' => $site['url'],
            'description' => $site['description'],
            'rating' => $this->faker->randomElement(['bad', 'average', 'good']),
        ];
    }
}
