<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $testUser2 = User::factory()->create([
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $categories = Category::factory(5)->create();

        $commentedUsers = User::factory(5)->create();

        $testUsers = [$testUser, $testUser2];

        Post::factory(10)->create()->each(function ($post) use ($categories, $commentedUsers, $testUsers) {
            $randomTestUser = $testUsers[array_rand($testUsers)];
            $post->update(['user_id' => $randomTestUser->id]);

            $post->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );

            for ($i = 0; $i < rand(1, 5); $i++) {
                $post->comments()->create([
                    'content' => fake()->sentence(),
                    'user_id' => $commentedUsers->random()->id,
                ]);
            }
        });
    }
}
