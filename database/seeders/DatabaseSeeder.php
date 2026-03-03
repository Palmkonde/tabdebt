<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Website;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)
            ->has(
                Group::factory()
                    ->count(3)
                    ->has(
                        Website::factory()->count(5)
                    )
            )
            ->create();
    }
}
