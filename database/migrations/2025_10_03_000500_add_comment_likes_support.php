<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('post_comments') && ! Schema::hasColumn('post_comments', 'likes_count')) {
            Schema::table('post_comments', function (Blueprint $table) {
                $table->unsignedInteger('likes_count')->default(0)->after('content');
            });
        }

        if (! Schema::hasTable('post_comment_likes')) {
            Schema::create('post_comment_likes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('comment_id');
                $table->unsignedBigInteger('user_id');
                $table->timestamps();

                $table->foreign('comment_id')->references('id')->on('post_comments')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->unique(['comment_id', 'user_id']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('post_comment_likes')) {
            Schema::dropIfExists('post_comment_likes');
        }
        if (Schema::hasTable('post_comments') && Schema::hasColumn('post_comments', 'likes_count')) {
            Schema::table('post_comments', function (Blueprint $table) {
                $table->dropColumn('likes_count');
            });
        }
    }
};

?>

