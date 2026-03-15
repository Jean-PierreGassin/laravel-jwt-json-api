<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JwtAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure a consistent secret for JWT signing during tests.
        config(['jwt.secret' => str_repeat('a', 64)]);
    }

    public function testLoginReturnsTokenForValidCredentials(): void
    {
        $password = 'password123';
        $user = User::factory()->create([
            'password' => bcrypt($password),
        ]);

        $response = $this->json('POST', '/api/user/login', [
            'data' => [
                'attributes' => [
                    'email' => $user->email,
                    'password' => $password,
                ],
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'type',
                    'attributes' => ['token'],
                ],
            ],
        ]);

        $token = $response->json('data.0.attributes.token');
        $this->assertNotEmpty($token);
    }

    public function testLoginRejectsInvalidCredentials(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('correct-password'),
        ]);

        $response = $this->json('POST', '/api/user/login', [
            'data' => [
                'attributes' => [
                    'email' => $user->email,
                    'password' => 'wrong-password',
                ],
            ],
        ]);

        $response->assertStatus(500);
        $response->assertJsonPath('errors.0.title', 'Authorization failed');
    }

    public function testMeRequiresAToken(): void
    {
        $response = $this->getJson('/api/me');

        $response->assertStatus(500);
        $response->assertJsonPath('errors.0.title', 'Authorization error');
    }

    public function testMeReturnsUserDetailsWithValidToken(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/me');

        $response->assertStatus(200);
        $response->assertJsonPath('data.0.type', 'user');
        $response->assertJsonPath('data.0.attributes.id', $user->id);
        $response->assertJsonPath('data.0.attributes.email', $user->email);
    }
}
