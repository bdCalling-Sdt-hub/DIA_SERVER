<?php

namespace Database\Seeders;

use App\Models\Story;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoriesTableSeeder extends Seeder
{

    public function run(): void
    {
        Story::factory()->count(10)->create();
    }
}
