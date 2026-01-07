<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add unique slug field for URL-safe conversation identification
     */
    public function up(): void
    {
        Schema::table('chat_conversations', function (Blueprint $table) {
            $table->string('slug', 16)->nullable()->unique()->after('id');
        });

        // Generate slugs for existing conversations
        $conversations = DB::table('chat_conversations')->whereNull('slug')->get();
        foreach ($conversations as $conversation) {
            DB::table('chat_conversations')
                ->where('id', $conversation->id)
                ->update(['slug' => Str::random(12)]);
        }

        // Make slug not nullable after populating
        Schema::table('chat_conversations', function (Blueprint $table) {
            $table->string('slug', 16)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_conversations', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
