<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Gộp tournaments và tournaments_management thành một bảng tournaments duy nhất
     */
    public function up(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            // Thêm các cột từ tournaments_management
            if (!Schema::hasColumn('tournaments', 'format')) {
                $table->enum('format', ['single_elimination', 'double_elimination', 'round_robin', 'swiss'])->default('single_elimination')->after('status');
            }
            if (!Schema::hasColumn('tournaments', 'competition_type')) {
                $table->enum('competition_type', ['individual', 'team'])->default('team')->after('format');
            }
            if (!Schema::hasColumn('tournaments', 'registration_deadline')) {
                $table->datetime('registration_deadline')->nullable()->after('end_date');
            }
            if (!Schema::hasColumn('tournaments', 'location_type')) {
                $table->enum('location_type', ['online', 'lan'])->default('online')->after('registration_deadline');
            }
            if (!Schema::hasColumn('tournaments', 'location_address')) {
                $table->string('location_address')->nullable()->after('location_type');
            }
            if (!Schema::hasColumn('tournaments', 'prize_distribution')) {
                $table->json('prize_distribution')->nullable()->after('prize_pool');
            }
            if (!Schema::hasColumn('tournaments', 'rules')) {
                $table->json('rules')->nullable()->after('prize_distribution');
            }
            if (!Schema::hasColumn('tournaments', 'image_url')) {
                $table->string('image_url')->nullable()->after('rules');
            }
            if (!Schema::hasColumn('tournaments', 'stream_link')) {
                $table->string('stream_link')->nullable()->after('image_url');
            }
            if (!Schema::hasColumn('tournaments', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('organizer_id')->constrained('users')->onDelete('set null');
            }
        });

        // Migrate dữ liệu từ tournaments_management sang tournaments (nếu có)
        if (Schema::hasTable('tournaments_management')) {
            DB::statement("
                UPDATE tournaments t
                INNER JOIN tournaments_management tm ON t.name = tm.name
                SET 
                    t.format = COALESCE(tm.tournament_format, 'single_elimination'),
                    t.competition_type = COALESCE(tm.competition_type, 'team'),
                    t.registration_deadline = tm.start_date,
                    t.location_type = COALESCE(tm.location_type, 'online'),
                    t.location_address = tm.location_address,
                    t.prize_distribution = tm.prize_structure,
                    t.rules = tm.rules_details,
                    t.image_url = tm.banner,
                    t.stream_link = tm.stream_link,
                    t.created_by = tm.created_by
                WHERE t.id IS NOT NULL
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn([
                'format',
                'competition_type',
                'registration_deadline',
                'location_type',
                'location_address',
                'prize_distribution',
                'rules',
                'image_url',
                'stream_link',
                'created_by'
            ]);
        });
    }
};

