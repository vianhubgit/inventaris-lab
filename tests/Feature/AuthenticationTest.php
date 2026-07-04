<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_accessible(): void
    {
        $this->get('/login')->assertOk()->assertSee('Username');
    }

    public function test_user_can_login_with_username(): void
    {
        $user = User::factory()->admin()->create([
            'username' => 'budiadmin',
            'password' => 'rahasia123',
        ]);

        $response = $this->post('/login', [
            'username' => 'budiadmin',
            'password' => 'rahasia123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect();
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create(['username' => 'someone', 'password' => 'correct123']);

        $this->post('/login', ['username' => 'someone', 'password' => 'salah'])
            ->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public function test_inactive_user_cannot_login(): void
    {
        User::factory()->inactive()->create(['username' => 'nonaktif', 'password' => 'rahasia123']);

        $this->post('/login', ['username' => 'nonaktif', 'password' => 'rahasia123'])
            ->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public function test_admin_is_redirected_to_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/')->assertRedirect(route('admin.dashboard'));
    }

    public function test_sekretaris_is_redirected_to_sekretaris_dashboard(): void
    {
        $sekretaris = User::factory()->sekretaris()->create();

        $this->actingAs($sekretaris)->get('/')->assertRedirect(route('sekretaris.dashboard'));
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/logout')->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
