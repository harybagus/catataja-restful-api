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
            "title" => "Basic Laravel Concepts",
            "description" => "This note provides an overview of Laravel's core features and functionality, helping beginners understand how to build robust web applications.",
            "pinned" => Pinned::FALSE->value,
            "user_id" => $user->id
        ]);

        Note::create([
            "title" => "Understanding RESTful APIs and Their Uses in Modern Applications",
            "description" => "Learn about RESTful APIs, how they work, and their importance in developing scalable web services. This note covers basic principles and implementation steps.",
            "pinned" => Pinned::TRUE->value,
            "user_id" => $user->id
        ]);

        Note::create([
            "title" => "Project Kickoff Meeting Notes",
            "description" => "Outline the main objectives, deliverables, and deadlines discussed during the project kickoff meeting. Ensure team alignment on responsibilities and expected outcomes.",
            "pinned" => Pinned::FALSE->value,
            "user_id" => $user->id
        ]);
    }
}
