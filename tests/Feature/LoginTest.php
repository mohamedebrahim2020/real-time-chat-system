<?php

	namespace Tests\Feature;

	use Illuminate\Foundation\Testing\RefreshDatabase;
	use Illuminate\Foundation\Testing\WithFaker;
	use Tests\TestCase;

	class LoginTest extends TestCase
	{
		use RefreshDatabase, WithFaker;

		/**
		 * A basic test example.
		 */
		public function test_login_returns_a_successful_response(): void
		{
			$data = [
				'email' => $this->faker->email,
				'password' => '123456789',
			];
			$response = $this->postJson(route('sanctum.login'), $data);
			$response->assertOk();
			$response->assertJsonStructure([
				'token',
			]);
			$this->assertDatabaseHas('users', ['email' => $data['email']]);
		}
	}
