<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class UserTest extends TestCase
{
    public function testRegisterSuccess()
    {
        $this->post("/api/users", [
            "email" => "bagus@gmail.com",
            "name" => "Bagus Hary",
            "password" => "password"
        ])->assertStatus(201)
            ->assertJson([
                "data" => [
                    "email" => "bagus@gmail.com",
                    "name" => "Bagus Hary"
                ]
            ]);
    }

    public function testRegisterFailed()
    {
        $this->post("/api/users", [
            "email" => "bagus",
            "name" => "",
            "password" => "123"
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "email" => [
                        "The email field must be a valid email address."
                    ],
                    "name" => [
                        "The name field is required."
                    ],
                    "password" => [
                        "The password field must be at least 6 characters."
                    ]
                ]
            ]);
    }

    public function testRegisterEmailAlreadyExists()
    {
        $this->testRegisterSuccess();

        $this->post("/api/users", [
            "email" => "bagus@gmail.com",
            "name" => "Bagus Hary",
            "password" => "password"
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "email" => [
                        "Email already registered."
                    ]
                ]
            ]);
    }

    public function testLoginSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->post("/api/users/login", [
            "email" => "test@gmail.com",
            "password" => "test123"
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    "email" => "test@gmail.com",
                    "name" => "test"
                ]
            ]);

        $user = User::where("email", "test@gmail.com")->first();
        self::assertNotNull($user->token);
    }

    public function testLoginFailedEmailNotFound()
    {
        $this->post("/api/users/login", [
            "email" => "test@gmail.com",
            "password" => "test123"
        ])->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "Email or password wrong."
                    ]
                ]
            ]);
    }

    public function testLoginFailedPasswordWrong()
    {
        $this->seed([UserSeeder::class]);

        $this->post("/api/users/login", [
            "email" => "test@gmail.com",
            "password" => "wrong123"
        ])->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "Email or password wrong."
                    ]
                ]
            ]);
    }

    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->get("/api/users/current", [
            "Authorization" => "test"
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    "email" => "test@gmail.com",
                    "name" => "test"
                ]
            ]);
    }

    public function testGetUnauthorized()
    {
        $this->seed([UserSeeder::class]);

        $this->get("/api/users/current")
            ->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "Unauthorized."
                    ]
                ]
            ]);
    }

    public function testGetInvalidToken()
    {
        $this->seed([UserSeeder::class]);

        $this->get("/api/users/current", [
            "Authorization" => "wrong"
        ])
            ->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "Unauthorized."
                    ]
                ]
            ]);
    }
}
