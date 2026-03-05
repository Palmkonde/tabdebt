<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Tag;
use App\Models\Website;
use App\Models\User;

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
        $palm =User::create([
            'name' => 'Palm',
            'email' => 'palm@example.com',
            'password' => Hash::make('password')
        ]);
        
        $tags = Tag::factory()->count(20)->create();

        Group::factory()
            ->count(3)
            ->for($palm)
            ->hasAttached($tags->random(3))
            ->has(
                Website::factory()
                    ->hasAttached($tags->random(2))
                    ->count(5)
            )
            ->create();

        User::factory(10)
            ->has(
                Group::factory()
                    ->hasAttached(
                        $tags->random(3)
                    )
                    ->has(
                        Website::factory()
                            ->hasAttached(
                                $tags->random(2)
                            )
                            ->count(5)
                    )
                    ->count(3)
            )
            ->create();
    }
}
