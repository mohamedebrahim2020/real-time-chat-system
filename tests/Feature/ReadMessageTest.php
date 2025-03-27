<?php

	namespace Tests\Feature;

	use App\Models\Message;
	use App\Models\User;
	use Illuminate\Foundation\Testing\RefreshDatabase;
	use Illuminate\Foundation\Testing\WithFaker;
	use Laravel\Sanctum\Sanctum;
	use Tests\TestCase;

	class ReadMessageTest extends TestCase
	{
		use RefreshDatabase, WithFaker;

		const USER_READ_MESSAGE_ROUTE = 'chat.read';

		/**
		 * A basic test example.
		 */
		public function test_read_message_successfully(): void
		{
			$sender = User::factory()->create();
			$receiver = User::factory()->create();
			$message = Message::factory()->create([
				'sender_id' => $sender->id,
				'receiver_id' => $receiver->id,
			]);

			$this->assertEquals('delivered', $message->status);

			Sanctum::actingAs($receiver);
			$response = $this->patchJson(route(self::USER_READ_MESSAGE_ROUTE, ['message_id' => $message->id]));
			$response->assertOk();
			$message->refresh();
			$this->assertEquals('read', $message->status);
		}

		/**
		 * A basic test example.
		 */
		public function test_read_message_invalid_as_message_not_belong_to_receiver(): void
		{
			$sender = User::factory()->create();
			$receiver = User::factory()->create();
			$receiver2 = User::factory()->create();
			$message = Message::factory()->create([
				'sender_id' => $sender->id,
				'receiver_id' => $receiver2->id,
			]);

			Sanctum::actingAs($receiver);
			$response = $this->patchJson(route(self::USER_READ_MESSAGE_ROUTE, ['message_id' => $message->id]));
			$response->assertBadRequest();
		}
	}
