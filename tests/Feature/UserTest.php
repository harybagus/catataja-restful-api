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

    public function testUpdateNameSuccess()
    {
        $this->seed([UserSeeder::class]);
        $oldUser = User::where("email", "test@gmail.com")->first();

        $this->patch(
            "/api/users/current",
            [
                "name" => "New Name"
            ],
            [
                "Authorization" => "test"
            ]
        )->assertStatus(200)
            ->assertJson([
                "data" => [
                    "email" => "test@gmail.com",
                    "name" => "New Name"
                ]
            ]);

        $newUser = User::where("email", "test@gmail.com")->first();
        self::assertNotEquals($oldUser->name, $newUser->name);
    }

    public function testUpdatePasswordSuccess()
    {
        $this->seed([UserSeeder::class]);
        $oldUser = User::where("email", "test@gmail.com")->first();

        $this->patch(
            "/api/users/current",
            [
                "password" => "new123"
            ],
            [
                "Authorization" => "test"
            ]
        )->assertStatus(200)
            ->assertJson([
                "data" => [
                    "email" => "test@gmail.com",
                    "name" => "test"
                ]
            ]);

        $newUser = User::where("email", "test@gmail.com")->first();
        self::assertNotEquals($oldUser->password, $newUser->password);
    }

    public function testUpdateFailed()
    {
        $this->seed([UserSeeder::class]);

        $this->patch(
            "/api/users/current",
            [
                "name" => "Bagus Bagus Bagus Bagus Bagus Bagus Bagus Bagus Bagus Bagus Bagus Bagus Bagus Bagus Bagus Bagus Bagus Bagus Bagus Bagus Bagus",
                "password" => "123"
            ],
            [
                "Authorization" => "test"
            ]
        )->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "name" => [
                        "The name field must not be greater than 100 characters."
                    ],
                    "password" => [
                        "The password field must be at least 6 characters."
                    ]
                ]
            ]);
    }

    public function testLogoutSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->delete(uri: "/api/users/logout", headers: [
            "Authorization" => "test"
        ])->assertStatus(200)
            ->assertJson([
                "data" => true
            ]);

        $user = User::where("email", "test@gmail.com")->first();
        self::assertNull($user->token);
    }

    public function testLogoutFailed()
    {
        $this->seed([UserSeeder::class]);

        $this->delete(uri: "/api/users/logout", headers: [
            "Authorization" => "wrong"
        ])->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "Unauthorized."
                    ]
                ]
            ]);
    }
}
