<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('honor_votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('honor_event_id');
            $table->foreignId('voter_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('voted_user_id')->constrained('users')->onDelete('cascade');
            $table->enum('vote_type', ['player', 'team', 'tournament', 'game']);
            $table->unsignedBigInteger('voted_item_id')->nullable(); // ID của player/team/tournament/game được vote
            $table->enum('voter_role', ['viewer', 'player', 'admin', 'super_admin']);
            $table->decimal('weight', 3, 1)->default(1.0); // Trọng số vote (1.0, 1.5, 2.0)
            $table->boolean('is_anonymous')->default(false); // Vote ẩn danh hay không
            $table->text('comment')->nullable(); // Bình luận kèm theo vote
            $table->timestamps();

            // Index để tối ưu query
            $table->index(['honor_event_id', 'voter_id']);
            $table->index(['honor_event_id', 'voted_user_id']);
            $table->index(['vote_type', 'voted_item_id']);

            // Unique constraint: mỗi user chỉ vote 1 lần cho 1 item trong 1 event
            $table->unique(['honor_event_id', 'voter_id', 'voted_item_id'], 'unique_vote_per_event');
        });

        // Add foreign key constraint after table creation
        Schema::table('honor_votes', function (Blueprint $table) {
            $table->foreign('honor_event_id')->references('id')->on('honor_events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('honor_votes');
    }
};
