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
        // Kiểm tra xem bảng tournaments có tồn tại không
        if (!Schema::hasTable('tournaments')) {
            // Nếu bảng tournaments chưa tồn tại, tạo mới với tất cả các cột
            Schema::create('tournaments', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->foreignId('game_id')->constrained('games')->onDelete('cascade');
                $table->foreignId('organizer_id')->nullable()->constrained('users')->onDelete('set null');
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                $table->datetime('start_date');
                $table->datetime('end_date');
                $table->datetime('registration_deadline')->nullable();
                $table->integer('max_teams')->nullable();
                $table->integer('max_participants')->nullable();
                $table->decimal('entry_fee', 10, 2)->default(0);
                $table->decimal('prize_pool', 15, 2)->default(0);
                // SQLite không hỗ trợ JSON type, dùng text thay thế
                if (config('database.default') === 'sqlite') {
                    $table->text('prize_distribution')->nullable();
                    $table->text('rules')->nullable();
                } else {
                    $table->json('prize_distribution')->nullable();
                    $table->json('rules')->nullable();
                }
                // SQLite không hỗ trợ enum, dùng string
                $isSqlite = config('database.default') === 'sqlite' || 
                            (config('database.connections.'.config('database.default').'.driver') === 'sqlite');
                if ($isSqlite) {
                    $table->string('status')->default('draft');
                    $table->string('format')->default('single_elimination');
                    $table->string('competition_type')->default('team');
                    $table->string('location_type')->default('online');
                } else {
                    $table->enum('status', ['draft', 'registration', 'ongoing', 'completed', 'cancelled'])->default('draft');
                    $table->enum('format', ['single_elimination', 'double_elimination', 'round_robin', 'swiss'])->default('single_elimination');
                    $table->enum('competition_type', ['individual', 'team'])->default('team');
                    $table->enum('location_type', ['online', 'lan'])->default('online');
                }
                $table->string('location_address')->nullable();
                $table->string('image_url')->nullable();
                $table->string('stream_link')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
            return;
        }

        $isSqlite = config('database.default') === 'sqlite' || 
                    (config('database.connections.'.config('database.default').'.driver') === 'sqlite');
        
        Schema::table('tournaments', function (Blueprint $table) use ($isSqlite) {
            // Thêm các cột từ tournaments_management
            if (!Schema::hasColumn('tournaments', 'format')) {
                if ($isSqlite) {
                    $table->string('format')->default('single_elimination');
                } else {
                    $table->enum('format', ['single_elimination', 'double_elimination', 'round_robin', 'swiss'])->default('single_elimination')->after('status');
                }
            }
            if (!Schema::hasColumn('tournaments', 'competition_type')) {
                if ($isSqlite) {
                    $table->string('competition_type')->default('team');
                } else {
                    $table->enum('competition_type', ['individual', 'team'])->default('team')->after('format');
                }
            }
            if (!Schema::hasColumn('tournaments', 'registration_deadline')) {
                if ($isSqlite) {
                    $table->datetime('registration_deadline')->nullable();
                } else {
                    $table->datetime('registration_deadline')->nullable()->after('end_date');
                }
            }
            if (!Schema::hasColumn('tournaments', 'location_type')) {
                if ($isSqlite) {
                    $table->string('location_type')->default('online');
                } else {
                    $table->enum('location_type', ['online', 'lan'])->default('online')->after('registration_deadline');
                }
            }
            if (!Schema::hasColumn('tournaments', 'location_address')) {
                if ($isSqlite) {
                    $table->string('location_address')->nullable();
                } else {
                    $table->string('location_address')->nullable()->after('location_type');
                }
            }
            if (!Schema::hasColumn('tournaments', 'prize_distribution')) {
                // SQLite không hỗ trợ JSON type, dùng text thay thế
                if ($isSqlite) {
                    $table->text('prize_distribution')->nullable();
                } else {
                    $table->json('prize_distribution')->nullable()->after('prize_pool');
                }
            }
            if (!Schema::hasColumn('tournaments', 'rules')) {
                // SQLite không hỗ trợ JSON type, dùng text thay thế
                if ($isSqlite) {
                    $table->text('rules')->nullable();
                } else {
                    $table->json('rules')->nullable()->after('prize_distribution');
                }
            }
            if (!Schema::hasColumn('tournaments', 'image_url')) {
                if ($isSqlite) {
                    $table->string('image_url')->nullable();
                } else {
                    $table->string('image_url')->nullable()->after('rules');
                }
            }
            if (!Schema::hasColumn('tournaments', 'stream_link')) {
                if ($isSqlite) {
                    $table->string('stream_link')->nullable();
                } else {
                    $table->string('stream_link')->nullable()->after('image_url');
                }
            }
            if (!Schema::hasColumn('tournaments', 'created_by')) {
                if ($isSqlite) {
                    $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                } else {
                    $table->foreignId('created_by')->nullable()->after('organizer_id')->constrained('users')->onDelete('set null');
                }
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

