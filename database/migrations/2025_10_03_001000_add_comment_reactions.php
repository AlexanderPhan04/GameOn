<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('post_comment_reactions')) {
            Schema::create('post_comment_reactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('comment_id');
                $table->unsignedBigInteger('user_id');
                $table->string('type', 16); // like,love,haha,wow,sad,angry
                $table->timestamps();

                $table->foreign('comment_id')->references('id')->on('post_comments')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->unique(['comment_id', 'user_id']);
                $table->index(['type']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('post_comment_reactions');
    }
};

?>

