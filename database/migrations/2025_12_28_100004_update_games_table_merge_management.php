<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Gộp games và games_management thành một bảng games duy nhất
     */
    public function up(): void
    {
        // Thêm các cột từ games_management vào games
        Schema::table('games', function (Blueprint $table) {
            // Thêm publisher
            if (!Schema::hasColumn('games', 'publisher')) {
                $table->string('publisher')->nullable()->after('genre');
            }
            // Thêm release_date
            if (!Schema::hasColumn('games', 'release_date')) {
                $table->date('release_date')->nullable()->after('publisher');
            }
            // Thêm banner_url (games có image_url, games_management có logo và banner)
            if (!Schema::hasColumn('games', 'banner_url')) {
                $table->string('banner_url')->nullable()->after('image_url');
            }
            // Đổi is_active thành status nếu cần (giữ cả hai để tương thích)
            if (!Schema::hasColumn('games', 'status')) {
                $table->enum('status', ['active', 'maintenance', 'discontinued'])->default('active')->after('is_active');
            }
            // Thêm is_esport_supported
            if (!Schema::hasColumn('games', 'is_esport_supported')) {
                $table->boolean('is_esport_supported')->default(false)->after('status');
            }
            // Thêm format_metadata (JSON chứa team_size, competition_formats, game_modes)
            if (!Schema::hasColumn('games', 'format_metadata')) {
                $table->json('format_metadata')->nullable()->after('is_esport_supported');
            }
            // Thêm official_website
            if (!Schema::hasColumn('games', 'official_website')) {
                $table->string('official_website')->nullable()->after('format_metadata');
            }
            // Thêm created_by và updated_by
            if (!Schema::hasColumn('games', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('official_website');
            }
            if (!Schema::hasColumn('games', 'updated_by')) {
                $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->after('created_by');
            }
        });

        // Migrate dữ liệu từ games_management sang games (nếu có)
        if (Schema::hasTable('games_management')) {
            DB::statement("
                INSERT INTO games (name, genre, publisher, release_date, description, image_url, banner_url, status, is_esport_supported, format_metadata, official_website, created_by, updated_by, created_at, updated_at)
                SELECT 
                    gm.name,
                    gm.genre,
                    gm.publisher,
                    gm.release_date,
                    COALESCE(gm.description, g.description) as description,
                    COALESCE(gm.logo, g.image_url) as image_url,
                    gm.banner as banner_url,
                    COALESCE(gm.status, CASE WHEN g.is_active = 1 THEN 'active' ELSE 'discontinued' END) as status,
                    gm.esport_support as is_esport_supported,
                    JSON_OBJECT(
                        'team_size', gm.team_size,
                        'competition_formats', gm.competition_formats,
                        'game_modes', gm.game_modes
                    ) as format_metadata,
                    gm.official_website,
                    gm.created_by,
                    gm.updated_by,
                    COALESCE(gm.created_at, g.created_at) as created_at,
                    COALESCE(gm.updated_at, g.updated_at) as updated_at
                FROM games_management gm
                LEFT JOIN games g ON g.name = gm.name
                WHERE NOT EXISTS (
                    SELECT 1 FROM games g2 WHERE g2.name = gm.name
                )
            ");

            // Update các game đã tồn tại với dữ liệu từ games_management
            DB::statement("
                UPDATE games g
                INNER JOIN games_management gm ON g.name = gm.name
                SET 
                    g.publisher = COALESCE(g.publisher, gm.publisher),
                    g.release_date = COALESCE(g.release_date, gm.release_date),
                    g.description = COALESCE(g.description, gm.description),
                    g.image_url = COALESCE(g.image_url, gm.logo),
                    g.banner_url = COALESCE(g.banner_url, gm.banner),
                    g.status = COALESCE(g.status, gm.status),
                    g.is_esport_supported = COALESCE(g.is_esport_supported, gm.esport_support),
                    g.format_metadata = COALESCE(g.format_metadata, JSON_OBJECT(
                        'team_size', gm.team_size,
                        'competition_formats', gm.competition_formats,
                        'game_modes', gm.game_modes
                    )),
                    g.official_website = COALESCE(g.official_website, gm.official_website),
                    g.created_by = COALESCE(g.created_by, gm.created_by),
                    g.updated_by = COALESCE(g.updated_by, gm.updated_by)
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn([
                'publisher',
                'banner_url',
                'is_esport_supported',
                'format_metadata',
                'official_website',
                'created_by',
                'updated_by'
            ]);
        });
    }
};

