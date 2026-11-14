<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PosAuthAndResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guests_are_redirected_to_login_for_all_protected_routes(): void
    {
        $customer = Customer::firstOrFail();
        $user = User::firstOrFail();

        $protectedGetRoutes = [
            '/dashboard',
            '/customers',
            '/customers/create',
            "/customers/{$customer->id}",
            "/customers/{$customer->id}/edit",
            '/users',
            '/users/create',
            "/users/{$user->id}",
            "/users/{$user->id}/edit",
            '/profile',
            '/avatars/sample.png',
        ];

        foreach ($protectedGetRoutes as $uri) {
            $this->get($uri)->assertRedirect('/login');
        }

        $this->post('/customers', [])->assertRedirect('/login');
        $this->put("/customers/{$customer->id}", [])->assertRedirect('/login');
        $this->delete("/customers/{$customer->id}")->assertRedirect('/login');

        $this->post('/users', [])->assertRedirect('/login');
        $this->put("/users/{$user->id}", [])->assertRedirect('/login');
        $this->delete("/users/{$user->id}")->assertRedirect('/login');
    }

    public function test_seeded_user_can_login_and_reach_dashboard_and_resource_pages(): void
    {
        $loginResp = $this->post('/login', [
            'email' => 'admin.reyes@example.com',
            'password' => 'password',
        ]);

        $loginResp->assertRedirect('/dashboard');
        $this->assertAuthenticated();

        $this->get('/dashboard')
            ->assertOk()
            ->assertSee('Operations Dashboard')
            ->assertSee('Carlos Reyes');

        $this->get('/customers')->assertOk();
        $this->get('/users')->assertOk();
    }

    public function test_registration_generates_unique_pos_username_and_grants_access(): void
    {
        $response = $this->post('/register', [
            'name' => 'Marco Diaz',
            'email' => 'admin.reyes@newstore.ph',
            'password' => 'SecretPass123!',
            'password_confirmation' => 'SecretPass123!',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();

        $registered = User::where('email', 'admin.reyes@newstore.ph')->firstOrFail();
        $this->assertSame('Marco Diaz', $registered->full_name);
        $this->assertSame('Staff Member', $registered->role);
        $this->assertNotSame('admin.reyes', $registered->username);
        $this->assertStringStartsWith('admin.reyes.', $registered->username);

        $this->get('/customers')->assertOk();
    }

    public function test_authenticated_user_can_show_and_destroy_customer_resource(): void
    {
        $actor = User::firstOrFail();
        $customer = Customer::orderByDesc('id')->firstOrFail();

        $this->actingAs($actor)
            ->get("/customers/{$customer->id}")
            ->assertOk()
            ->assertSee($customer->full_name)
            ->assertSee($customer->email);

        $destroyResp = $this->actingAs($actor)
            ->delete("/customers/{$customer->id}");

        $destroyResp->assertRedirect('/customers');
        $destroyResp->assertSessionHas('success', 'Customer account deleted successfully.');
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }

    public function test_authenticated_user_can_show_and_destroy_other_user_and_clean_avatar_but_not_self(): void
    {
        Storage::fake('public');

        $actor = User::orderBy('id')->firstOrFail();
        $other = User::where('id', '!=', $actor->id)->firstOrFail();

        // Give target user an avatar via UserController@update first
        $upload = UploadedFile::fake()->image('staff.png', 300, 300);
        $this->actingAs($actor)->put("/users/{$other->id}", [
            'username' => $other->username,
            'full_name' => $other->full_name,
            'email' => $other->email,
            'role' => $other->role,
            'avatar' => $upload,
        ])->assertRedirect('/users');

        $other->refresh();
        $avatarPath = $other->avatar;
        $this->assertNotNull($avatarPath);
        Storage::disk('public')->assertExists($avatarPath);

        // UserController@show renders detail page
        $this->actingAs($actor)
            ->get("/users/{$other->id}")
            ->assertOk()
            ->assertSee($other->full_name)
            ->assertSee($other->username);

        // Deleting signed-in self from /users/{self} is blocked safely
        $selfDeleteResp = $this->actingAs($actor)->delete("/users/{$actor->id}");
        $selfDeleteResp->assertRedirect('/users');
        $selfDeleteResp->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $actor->id]);

        // Deleting another user deletes DB row and stored avatar file
        $otherDeleteResp = $this->actingAs($actor)->delete("/users/{$other->id}");
        $otherDeleteResp->assertRedirect('/users');
        $otherDeleteResp->assertSessionHas('success', 'User account deleted successfully.');
        $this->assertDatabaseMissing('users', ['id' => $other->id]);
        Storage::disk('public')->assertMissing($avatarPath);
    }
}
