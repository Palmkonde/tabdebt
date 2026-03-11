<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Tag;
use App\Models\User;
use App\Models\Website;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    // use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $palm = User::create([
            'name' => 'Palm',
            'email' => 'palm@example.com',
            'password' => Hash::make('password'),
        ]);

        $tags = Tag::factory()->count(20)->create();

        Group::factory()
            ->count(3)
            ->for($palm)
            ->has(Website::factory()->count(5))
            ->afterCreating(function (Group $group) use ($tags) {
                $group->tags()->attach($tags->random(3));
                $group->websites->each(function (Website $website) use ($tags) {
                    $website->tags()->attach($tags->random(2));
                });
            })
            ->create();

        User::factory(10)
            ->has(
                Group::factory()
                    ->has(Website::factory()->count(5))
                    ->afterCreating(function (Group $group) use ($tags) {
                        $group->tags()->attach($tags->random(3));
                        $group->websites->each(function (Website $website) use ($tags) {
                            $website->tags()->attach($tags->random(2));
                        });
                    })
                    ->count(3)
            )
            ->create();
    }
}
