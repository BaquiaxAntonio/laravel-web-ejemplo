<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Comment;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(20)->create();
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $categories = Category::factory(4)->create();
       
        $questions = Question::factory(30)->create([
            'category_id' => fn() => $categories->random()->id,
            'user_id' => fn() => User::inRandomOrder()->first()->id,
        ]);

        $answers = Answer::factory(50)->create([
            'question_id' => fn() => $questions->random()->id,
            'user_id' => fn() => User::inRandomOrder()->first()->id,
        ]);

        Comment::factory(100)->create([
            'commentable_id' => fn() => $questions->random()->id,
            'commentable_type' => Question::class,
            'user_id' => fn() => User::inRandomOrder()->first()->id,
        ]);
  
    }
}
