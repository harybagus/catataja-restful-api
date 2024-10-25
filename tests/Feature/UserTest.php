<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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
                        "Email already registered"
                    ]
                ]
            ]);
    }
}
