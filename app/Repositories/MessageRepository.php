<?php

namespace App\Repositories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;

class MessageRepository extends BaseRepository
{
    /**
     * MessageRepository constructor.
     *
     * @param Message $model
     */
    public function __construct(Message $model)
    {
        parent::__construct($model);
    }

	/**
	 *
	 * @return Collection
	 */
	public function AllChatWithUser($userId1)
	{
		$userId2 = auth()->user()?->id;
		return $this->model->where(function($query) use ($userId1, $userId2) {
			$query->where('sender_id', $userId1)
				->where('receiver_id', $userId2);
		})
			->orWhere(function($query) use ($userId1, $userId2) {
				$query->where('sender_id', $userId2)
					->where('receiver_id', $userId1);
			})
			->orderBy('created_at', 'desc')
			->paginate();
	}
}
