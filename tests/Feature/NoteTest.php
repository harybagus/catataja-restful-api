<?php

namespace Tests\Feature;

use App\Enums\Pinned;
use Database\Seeders\NoteSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NoteTest extends TestCase
{
    public function testCreateSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->post(
            "/api/notes",
            [
                "title" => "Lorem",
                "description"  => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s."
            ],
            [
                "Authorization" => "test"
            ]
        )->assertStatus(201)
            ->assertJson([
                "data" => [
                    "title" => "Lorem",
                    "description"  => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.",
                    "pinned" => "false"
                ]
            ]);
    }

    public function testCreateFailed()
    {
        $this->seed([UserSeeder::class]);

        $this->post(
            "/api/notes",
            [
                "title" => "",
                "description"  => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s. It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).",
                "pinned" => Pinned::TRUE->value
            ],
            [
                "Authorization" => "test"
            ]
        )->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "title" => [
                        "The title field is required."
                    ],
                    "description" => [
                        "The description field must not be greater than 255 characters."
                    ]
                ]
            ]);
    }

    public function testCreateUnauthorized()
    {
        $this->seed([UserSeeder::class]);

        $this->post(
            "/api/notes",
            [
                "title" => "Lorem",
                "description"  => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s."
            ],
            [
                "Authorization" => "wrong"
            ]
        )->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "Unauthorized."
                    ]
                ]
            ]);
    }

    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class, NoteSeeder::class]);

        $this->get("/api/notes", [
            "Authorization" => "test"
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "title" => "Getting Started with Laravel for Beginners",
                        "description" => "This note provides a basic introduction to Laravel, covering installation, setup, and the essential components needed to build your first web application.",
                        "pinned" => "false"
                    ],
                    [
                        "title" => "Understanding RESTful APIs and Their Uses in Modern Applications",
                        "description" => "Learn about RESTful APIs, how they work, and their importance in developing scalable web services. This note covers basic principles and implementation steps.",
                        "pinned" => "true"
                    ]
                ]
            ]);
    }

    public function testGetNotFound()
    {
        $this->seed([UserSeeder::class]);

        $this->get("/api/notes", [
            "Authorization" => "test"
        ])->assertStatus(404)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "No notes found."
                    ]
                ]
            ]);
    }
}
