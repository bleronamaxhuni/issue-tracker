<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['name' => 'bug', 'color' => '#ef4444'],
            ['name' => 'feature', 'color' => '#3b82f6'],
            ['name' => 'enhancement', 'color' => '#8b5cf6'],
            ['name' => 'documentation', 'color' => '#6b7280'],
            ['name' => 'urgent', 'color' => '#f97316'],
            ['name' => 'backend', 'color' => '#14b8a6'],
            ['name' => 'frontend', 'color' => '#ec4899'],
        ];

        foreach ($tags as $tag) {
            Tag::query()->create($tag);
        }
    }
}
