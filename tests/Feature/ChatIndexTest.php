<?php

	namespace Tests\Feature;

	use App\Models\Message;
	use App\Models\User;
	use Illuminate\Foundation\Testing\RefreshDatabase;
	use Illuminate\Foundation\Testing\WithFaker;
	use Laravel\Sanctum\Sanctum;
	use Tests\TestCase;

	class ChatIndexTest extends TestCase
	{
		use RefreshDatabase, WithFaker;

		const USER_CHAT_INDEX_ROUTE = 'chat.index';

		/**
		 * A basic test example.
		 */
		public function test_chat_index_successfully(): void
		{
			$sender = User::factory()->create();
			$receiver = User::factory()->create();
			Message::factory(5)->create([
				'sender_id' => $sender->id,
				'receiver_id' => $receiver->id,
			]);

			Sanctum::actingAs($sender);
			$response = $this->getJson(route(self::USER_CHAT_INDEX_ROUTE, ['user_id' => $receiver->id]));
			$response->assertOk();
			$response->assertJsonStructure([
				'data' => [
					'*' => [
						'id',
						'message',
						'sender_id',
						'receiver_id',
						'status',
						'created_at',
						'updated_at',
					]
				],
				'meta' => [
					'current_page',
					'total',
				]
			]);
			$this->assertDatabaseCount('messages', 5);
		}

		/**
		 * A basic test example.
		 */
		public function test_chat_index_invalid(): void
		{
			$sender = User::factory()->create();
			$receiver = User::factory()->create();
			Message::factory(5)->create([
				'sender_id' => $sender->id,
				'receiver_id' => $receiver->id,
			]);

			Sanctum::actingAs($sender);
			$response = $this->getJson(route(self::USER_CHAT_INDEX_ROUTE, ['user_id' => 20]));
			$response->assertUnprocessable();
			$response->assertInvalid('user_id');
			$this->assertDatabaseCount('messages', 5);
		}
	}
