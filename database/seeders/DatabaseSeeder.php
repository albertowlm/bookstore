<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         \App\Models\Book::factory(20)->create();
         \App\Models\Author::factory(20)->create();
         \App\Models\Subject::factory(20)->create();
    }
}
