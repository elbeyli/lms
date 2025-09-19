<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);
    }

    public function test_unauthenticated_user_cannot_access_profile(): void
    {
        $response = $this->get('/profile');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_profile(): void
    {
        $response = $this->actingAs($this->user)->get('/profile');

        $response->assertStatus(200);
        $response->assertViewIs('user.profile');
        $response->assertViewHas('user', $this->user);
        $response->assertSee($this->user->name);
        $response->assertSee($this->user->email);
    }

    public function test_authenticated_user_can_view_settings(): void
    {
        $response = $this->actingAs($this->user)->get('/settings');

        $response->assertStatus(200);
        $response->assertViewIs('user.settings');
        $response->assertSee('Settings');
        $response->assertSee('Study Preferences');
    }

    public function test_user_can_update_profile_information(): void
    {
        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];

        $response = $this->actingAs($this->user)
            ->put('/profile', $updateData);

        $response->assertRedirect('/profile');
        $response->assertSessionHas('success', 'Profile updated successfully.');

        $this->user->refresh();
        $this->assertEquals('Updated Name', $this->user->name);
        $this->assertEquals('updated@example.com', $this->user->email);
    }

    public function test_user_can_update_password(): void
    {
        $updateData = [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];

        $response = $this->actingAs($this->user)
            ->put('/profile', $updateData);

        $response->assertRedirect('/profile');
        $response->assertSessionHas('success', 'Profile updated successfully.');

        $this->user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $this->user->password));
    }

    public function test_profile_update_validation_rules(): void
    {
        // Test empty name
        $response = $this->actingAs($this->user)
            ->put('/profile', [
                'name' => '',
                'email' => $this->user->email,
            ]);
        $response->assertSessionHasErrors(['name']);

        // Test invalid email
        $response = $this->actingAs($this->user)
            ->put('/profile', [
                'name' => $this->user->name,
                'email' => 'invalid-email',
            ]);
        $response->assertSessionHasErrors(['email']);

        // Test short password
        $response = $this->actingAs($this->user)
            ->put('/profile', [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'password' => '123',
                'password_confirmation' => '123',
            ]);
        $response->assertSessionHasErrors(['password']);

        // Test password confirmation mismatch
        $response = $this->actingAs($this->user)
            ->put('/profile', [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'password' => 'password123',
                'password_confirmation' => 'different123',
            ]);
        $response->assertSessionHasErrors(['password']);
    }

    public function test_ajax_profile_update_returns_json(): void
    {
        $updateData = [
            'name' => 'AJAX Updated Name',
            'email' => 'ajax@example.com',
        ];

        $response = $this->actingAs($this->user)
            ->putJson('/profile', $updateData);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Profile updated successfully.',
        ]);

        $this->user->refresh();
        $this->assertEquals('AJAX Updated Name', $this->user->name);
        $this->assertEquals('ajax@example.com', $this->user->email);
    }

    public function test_unique_email_validation(): void
    {
        $otherUser = User::create([
            'name' => 'Other User',
            'email' => 'other@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($this->user)
            ->put('/profile', [
                'name' => $this->user->name,
                'email' => 'other@example.com', // Try to use other user's email
            ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_back_arrow_navigation_link(): void
    {
        $response = $this->actingAs($this->user)->get('/profile');

        $response->assertStatus(200);
        $response->assertSee('href="'.route('dashboard').'"', false);
    }

    public function test_dashboard_loads_correctly(): void
    {
        $response = $this->actingAs($this->user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
    }

    public function test_back_arrow_actually_navigates_to_dashboard(): void
    {
        // First go to profile
        $this->actingAs($this->user)->get('/profile')->assertStatus(200);

        // Then navigate to dashboard (simulating back arrow click)
        $response = $this->actingAs($this->user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
    }
}
