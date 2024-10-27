<?php

namespace Database\Seeders;

use App\Enums\Pinned;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where("email", "test@gmail.com")->first();

        Note::create([
            "title" => "Getting Started with Laravel for Beginners",
            "description" => "This note provides a basic introduction to Laravel, covering installation, setup, and the essential components needed to build your first web application.",
            "pinned" => Pinned::FALSE->value,
            "user_id" => $user->id
        ]);

        Note::create([
            "title" => "Understanding RESTful APIs and Their Uses in Modern Applications",
            "description" => "Learn about RESTful APIs, how they work, and their importance in developing scalable web services. This note covers basic principles and implementation steps.",
            "pinned" => Pinned::TRUE->value,
            "user_id" => $user->id
        ]);
    }
}
