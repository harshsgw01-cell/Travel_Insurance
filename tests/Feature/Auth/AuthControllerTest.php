<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::findOrCreate('Customer');
        Role::findOrCreate('Admin');
        Role::findOrCreate('Agent');
    }

    public function test_register_with_valid_credentials()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password123!',
            'role' => 'Customer',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'roles',
                    ],
                    'token',
                ],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'User registered successfully',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
        ]);
    }

    public function test_register_assigns_correct_role()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => 'SecurePass123!',
            'role' => 'Agent',
        ]);

        $response->assertStatus(201);

        $user = User::where('email', 'jane@example.com')->first();
        $this->assertTrue($user->hasRole('Agent'));
    }

    public function test_register_defaults_to_customer_role()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Bob Wilson',
            'email' => 'bob@example.com',
            'password' => 'Password456!',
        ]);

        $response->assertStatus(201);

        $user = User::where('email', 'bob@example.com')->first();
        $this->assertTrue($user->hasRole('Customer'));
    }

    public function test_register_without_name()
    {
        $response = $this->postJson('/api/register', [
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'role' => 'Customer',
        ]);

        $response->assertStatus(422);
    }

    public function test_register_without_email()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'password' => 'Password123!',
            'role' => 'Customer',
        ]);

        $response->assertStatus(422);
    }

    public function test_register_without_password()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'Customer',
        ]);

        $response->assertStatus(422);
    }

    public function test_register_with_duplicate_email()
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->postJson('/api/register', [
            'name' => 'Another User',
            'email' => 'existing@example.com',
            'password' => 'Password123!',
            'role' => 'Customer',
        ]);

        $response->assertStatus(422);
    }

    public function test_register_generates_authentication_token()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Token User',
            'email' => 'token@example.com',
            'password' => 'Password123!',
            'role' => 'Customer',
        ]);

        $data = $response->json('data');
        $this->assertNotEmpty($data['token']);

        $user = User::where('email', 'token@example.com')->first();
        $this->assertTrue($user->tokens()->count() > 0);
    }

    public function test_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => bcrypt('Password123!'),
        ]);
        $user->assignRole('Customer');

        $response = $this->postJson('/api/login', [
            'email' => 'login@example.com',
            'password' => 'Password123!',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'roles',
                    ],
                    'token',
                ],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Login successful',
            ]);
    }

    public function test_login_with_invalid_email()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'Password123!',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid login credentials',
            ]);
    }

    public function test_login_with_wrong_password()
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('CorrectPassword123!'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'WrongPassword123!',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid login credentials',
            ]);
    }

    public function test_login_without_email()
    {
        $response = $this->postJson('/api/login', [
            'password' => 'Password123!',
        ]);

        $response->assertStatus(422);
    }

    public function test_login_without_password()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(422);
    }

    public function test_login_generates_authentication_token()
    {
        $user = User::factory()->create([
            'email' => 'token.login@example.com',
            'password' => bcrypt('Password123!'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'token.login@example.com',
            'password' => 'Password123!',
        ]);

        $data = $response->json('data');
        $this->assertNotEmpty($data['token']);
    }

    public function test_logout_with_authenticated_user()
    {
        $user = User::factory()->create();
        $token = $user->createToken('api')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Logout successful',
            ]);
    }

    public function test_logout_without_authentication()
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401);
    }

    public function test_logout_revokes_token()
    {
        $user = User::factory()->create();
        $token = $user->createToken('api')->plainTextToken;

        $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/logout');

        $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/dashboard/admin')
            ->assertStatus(401);
    }
}
