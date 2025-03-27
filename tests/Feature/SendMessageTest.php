<?php

	namespace Tests\Feature;

	use App\Events\MessageSent;
	use App\Models\User;
	use Illuminate\Foundation\Testing\RefreshDatabase;
	use Illuminate\Foundation\Testing\WithFaker;
	use Illuminate\Support\Facades\Event;
	use Laravel\Sanctum\Sanctum;
	use Tests\TestCase;

	class SendMessageTest extends TestCase
	{
		use RefreshDatabase, WithFaker;

		const USER_SEND_MESSAGE_ROUTE = 'chat.send';
		/**
		 * A basic test example.
		 */
		public function test_send_message_successfully(): void
		{
			Event::fake();
			$sender = User::factory()->create();
			$receiver = User::factory()->create();

			Sanctum::actingAs($sender);
			$data = [
				'message' => $message = $this->faker->text(50),
				'receiver_id' => $receiver->id,
			];
			$response = $this->postJson(route(self::USER_SEND_MESSAGE_ROUTE), $data);
			$response->assertCreated();
			$response->assertJsonStructure([]);
			$this->assertDatabaseHas('messages', $data);
			Event::assertDispatched(MessageSent::class, function ($event) use ($sender, $receiver, $message) {
				return $event->message->sender_id === $sender->id &&
					$event->message->receiver_id === $receiver->id &&
					$event->message->message === $message;
			});
		}

		/**
		 * A basic test example.
		 */
		public function test_send_message_invalid(): void
		{
			Event::fake();
			$sender = User::factory()->create();

			Sanctum::actingAs($sender);
			$data = [
				'message' => $this->faker->text(50),
				'receiver_id' => 22,
			];
			$response = $this->postJson(route(self::USER_SEND_MESSAGE_ROUTE), $data);
			$response->assertUnprocessable();
			$response->assertInvalid('receiver_id');
			$this->assertDatabaseMissing('messages', $data);
			Event::assertNotDispatched(MessageSent::class);
		}
	}
