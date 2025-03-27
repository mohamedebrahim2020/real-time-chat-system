<?php

namespace App\Services;

use App\Repositories\MessageRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class MessageService extends BaseService
{
    public function __construct(MessageRepository $repository)
    {
        $this->repository = $repository;
    }

	/**
	 *
	 * @return Collection
	 */
	public function AllChatWithUser($userId)
	{
		return $this->repository->AllChatWithUser($userId);
	}

	public function markMessageAsRead($messageId)
	{
		$message = $this->show($messageId);
		$this->checkMessageBelongToAuthUser($message);
		$this->update(['status' => 1], $messageId);
	}

	private function checkMessageBelongToAuthUser($message)
	{
		if (auth()->id() !== $message->receiver_id) {
			throw new HttpResponseException(response()->json([
				'error' => 'Message not sent to you.'
			], Response::HTTP_BAD_REQUEST));
		}
	}
}
