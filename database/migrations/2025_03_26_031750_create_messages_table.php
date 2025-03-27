<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'sender_id')->constrained();
            $table->foreignIdFor(User::class, 'receiver_id')->constrained();
            $table->text('message');
            $table->tinyInteger('status')->default(0);
			$table->index(['sender_id', 'receiver_id']); //Optimizes queries that search for conversations between two users.
			$table->timestamps();
			$table->index('created_at'); // Sorting or fetching recent messages becomes faster.


		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
