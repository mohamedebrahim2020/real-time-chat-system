<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
	use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'status'
    ];

	/**
	 * Get the user's first name.
	 *
	 * @return \Illuminate\Database\Eloquent\Casts\Attribute
	 */
	protected function status(): Attribute
	{
		$statuses = ['delivered', 'read'];
		return Attribute::make(
			get: fn (int $value) => $statuses[$value],
		);

	}
}
