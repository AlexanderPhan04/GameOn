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
        // Kiểm tra xem bảng games có tồn tại không
        if (!Schema::hasTable('games')) {
            // Nếu bảng games chưa tồn tại, tạo mới với tất cả các cột
            // Lưu ý: Trong môi trường test, có thể bảng chưa được tạo
            Schema::create('games', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('genre')->nullable();
                $table->string('developer')->nullable();
                $table->string('publisher')->nullable();
                $table->date('release_date')->nullable();
                $table->string('platform')->nullable();
                $table->string('image_url')->nullable();
                $table->string('banner_url')->nullable();
                $table->boolean('is_active')->default(true);
                // SQLite không hỗ trợ enum, dùng string với check constraint
                if (config('database.default') === 'sqlite' || 
                    (config('database.connections.'.config('database.default').'.driver') === 'sqlite')) {
                    $table->string('status')->default('active');
                } else {
                    $table->enum('status', ['active', 'maintenance', 'discontinued'])->default('active');
                }
                $table->boolean('is_esport_supported')->default(false);
                // SQLite không hỗ trợ JSON type, dùng text thay thế
                if (config('database.default') === 'sqlite') {
                    $table->text('format_metadata')->nullable();
                } else {
                    $table->json('format_metadata')->nullable();
                }
                $table->string('official_website')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();
            });
            return;
        }

        // Thêm các cột từ games_management vào games (nếu bảng đã tồn tại)
        $isSqlite = config('database.default') === 'sqlite' || 
                    (config('database.connections.'.config('database.default').'.driver') === 'sqlite');
        
        Schema::table('games', function (Blueprint $table) use ($isSqlite) {
            // Thêm publisher
            if (!Schema::hasColumn('games', 'publisher')) {
                if ($isSqlite) {
                    $table->string('publisher')->nullable();
                } else {
                    $table->string('publisher')->nullable()->after('genre');
                }
            }
            // Thêm release_date
            if (!Schema::hasColumn('games', 'release_date')) {
                if ($isSqlite) {
                    $table->date('release_date')->nullable();
                } else {
                    $table->date('release_date')->nullable()->after('publisher');
                }
            }
            // Thêm banner_url (games có image_url, games_management có logo và banner)
            if (!Schema::hasColumn('games', 'banner_url')) {
                if ($isSqlite) {
                    $table->string('banner_url')->nullable();
                } else {
                    $table->string('banner_url')->nullable()->after('image_url');
                }
            }
            // Đổi is_active thành status nếu cần (giữ cả hai để tương thích)
            if (!Schema::hasColumn('games', 'status')) {
                if ($isSqlite) {
                    $table->string('status')->default('active');
                } else {
                    $table->enum('status', ['active', 'maintenance', 'discontinued'])->default('active')->after('is_active');
                }
            }
            // Thêm is_esport_supported
            if (!Schema::hasColumn('games', 'is_esport_supported')) {
                if ($isSqlite) {
                    $table->boolean('is_esport_supported')->default(false);
                } else {
                    $table->boolean('is_esport_supported')->default(false)->after('status');
                }
            }
            // Thêm format_metadata (JSON chứa team_size, competition_formats, game_modes)
            if (!Schema::hasColumn('games', 'format_metadata')) {
                // SQLite không hỗ trợ JSON type, dùng text thay thế
                if ($isSqlite) {
                    $table->text('format_metadata')->nullable();
                } else {
                    $table->json('format_metadata')->nullable()->after('is_esport_supported');
                }
            }
            // Thêm official_website
            if (!Schema::hasColumn('games', 'official_website')) {
                if ($isSqlite) {
                    $table->string('official_website')->nullable();
                } else {
                    $table->string('official_website')->nullable()->after('format_metadata');
                }
            }
            // Thêm created_by và updated_by
            if (!Schema::hasColumn('games', 'created_by')) {
                if ($isSqlite) {
                    $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                } else {
                    $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('official_website');
                }
            }
            if (!Schema::hasColumn('games', 'updated_by')) {
                if ($isSqlite) {
                    $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
                } else {
                    $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->after('created_by');
                }
            }
        });

        // Migrate dữ liệu từ games_management sang games (nếu có)
        if (Schema::hasTable('games_management')) {
            // Lấy tất cả games_management records
            $managementGames = DB::table('games_management')->get();
            
            foreach ($managementGames as $gm) {
                $existingGame = DB::table('games')->where('name', $gm->name)->first();
                
                $formatMetadata = json_encode([
                    'team_size' => $gm->team_size ?? null,
                    'competition_formats' => $gm->competition_formats ?? null,
                    'game_modes' => $gm->game_modes ?? null,
                ]);
                
                if ($existingGame) {
                    // Update game đã tồn tại
                    DB::table('games')->where('id', $existingGame->id)->update([
                        'publisher' => $existingGame->publisher ?? $gm->publisher,
                        'release_date' => $existingGame->release_date ?? $gm->release_date,
                        'description' => $existingGame->description ?? $gm->description,
                        'image_url' => $existingGame->image_url ?? ($gm->logo ?? null),
                        'banner_url' => $existingGame->banner_url ?? ($gm->banner ?? null),
                        'status' => $existingGame->status ?? ($gm->status ?? 'active'),
                        'is_esport_supported' => $existingGame->is_esport_supported ?? ($gm->esport_support ?? false),
                        'format_metadata' => $existingGame->format_metadata ?? $formatMetadata,
                        'official_website' => $existingGame->official_website ?? $gm->official_website,
                        'created_by' => $existingGame->created_by ?? $gm->created_by,
                        'updated_by' => $existingGame->updated_by ?? $gm->updated_by,
                    ]);
                } else {
                    // Insert game mới
                    DB::table('games')->insert([
                        'name' => $gm->name,
                        'genre' => $gm->genre ?? null,
                        'publisher' => $gm->publisher ?? null,
                        'release_date' => $gm->release_date ?? null,
                        'description' => $gm->description ?? null,
                        'image_url' => $gm->logo ?? null,
                        'banner_url' => $gm->banner ?? null,
                        'status' => $gm->status ?? 'active',
                        'is_esport_supported' => $gm->esport_support ?? false,
                        'format_metadata' => $formatMetadata,
                        'official_website' => $gm->official_website ?? null,
                        'created_by' => $gm->created_by ?? null,
                        'updated_by' => $gm->updated_by ?? null,
                        'created_at' => $gm->created_at ?? now(),
                        'updated_at' => $gm->updated_at ?? now(),
                    ]);
                }
            }
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

