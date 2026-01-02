<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migration 11: Tách bảng tournaments thành 3 bảng nhỏ hơn
 *
 * tournaments (core): id, name, description, game_id, created_by, status, image_url, stream_link
 * tournament_settings: prize_pool, prize_distribution, max_teams, format, competition_type, rules
 * tournament_schedule: start_date, end_date, registration_deadline, location_type, location_address
 */
return new class extends Migration
{
    public function up(): void
    {
        $isSqlite = config('database.default') === 'sqlite' ||
            (config('database.connections.' . config('database.default') . '.driver') === 'sqlite');

        // ========== 1. Tạo bảng tournament_settings ==========
        Schema::create('tournament_settings', function (Blueprint $table) use ($isSqlite) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->decimal('prize_pool', 10, 2)->default(0);
            $table->json('prize_distribution')->nullable();
            $table->integer('max_teams')->default(32);

            if ($isSqlite) {
                $table->string('format')->default('single_elimination');
                $table->string('competition_type')->default('individual');
            } else {
                $table->enum('format', ['single_elimination', 'double_elimination', 'round_robin', 'swiss'])
                    ->default('single_elimination');
                $table->enum('competition_type', ['individual', 'team', 'mixed'])
                    ->default('individual');
            }

            $table->json('rules')->nullable();
            $table->timestamps();

            $table->unique('tournament_id');
        });

        // ========== 2. Tạo bảng tournament_schedule ==========
        Schema::create('tournament_schedule', function (Blueprint $table) use ($isSqlite) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->datetime('registration_deadline')->nullable();

            if ($isSqlite) {
                $table->string('location_type')->default('online');
            } else {
                $table->enum('location_type', ['online', 'lan'])->default('online');
            }

            $table->string('location_address', 255)->nullable();
            $table->timestamps();

            $table->unique('tournament_id');
            $table->index('start_date');
            $table->index('registration_deadline');
        });

        // ========== 3. Migrate data từ tournaments sang các bảng mới ==========
        $tournaments = DB::table('tournaments')->get();

        foreach ($tournaments as $tournament) {
            // Insert vào tournament_settings
            DB::table('tournament_settings')->insert([
                'tournament_id' => $tournament->id,
                'prize_pool' => $tournament->prize_pool ?? 0,
                'prize_distribution' => $tournament->prize_distribution,
                'max_teams' => $tournament->max_teams ?? 32,
                'format' => $tournament->format ?? 'single_elimination',
                'competition_type' => $tournament->competition_type ?? 'individual',
                'rules' => $tournament->rules,
                'created_at' => $tournament->created_at,
                'updated_at' => $tournament->updated_at,
            ]);

            // Insert vào tournament_schedule
            DB::table('tournament_schedule')->insert([
                'tournament_id' => $tournament->id,
                'start_date' => $tournament->start_date,
                'end_date' => $tournament->end_date,
                'registration_deadline' => $tournament->registration_deadline,
                'location_type' => $tournament->location_type ?? 'online',
                'location_address' => $tournament->location_address,
                'created_at' => $tournament->created_at,
                'updated_at' => $tournament->updated_at,
            ]);
        }

        // ========== 4. Drop các cột đã chuyển từ tournaments (CHỈ MySQL) ==========
        if (! $isSqlite) {
            Schema::table('tournaments', function (Blueprint $table) {
                $table->dropColumn([
                    'prize_pool',
                    'prize_distribution',
                    'max_teams',
                    'format',
                    'competition_type',
                    'rules',
                    'start_date',
                    'end_date',
                    'registration_deadline',
                    'location_type',
                    'location_address',
                ]);
            });
        }
    }

    public function down(): void
    {
        $isSqlite = config('database.default') === 'sqlite' ||
            (config('database.connections.' . config('database.default') . '.driver') === 'sqlite');

        // Thêm lại các cột vào tournaments (CHỈ MySQL vì SQLite không drop)
        if (! $isSqlite) {
            Schema::table('tournaments', function (Blueprint $table) {
                $table->decimal('prize_pool', 10, 2)->default(0)->after('created_by');
                $table->json('prize_distribution')->nullable()->after('prize_pool');
                $table->integer('max_teams')->default(32)->after('prize_distribution');
                $table->enum('format', ['single_elimination', 'double_elimination', 'round_robin', 'swiss'])
                    ->default('single_elimination')->after('max_teams');
                $table->enum('competition_type', ['individual', 'team', 'mixed'])
                    ->default('individual')->after('format');
                $table->enum('status', ['registration', 'ongoing', 'completed', 'cancelled'])
                    ->default('registration')->after('competition_type');
                $table->datetime('start_date')->nullable()->after('status');
                $table->datetime('end_date')->nullable()->after('start_date');
                $table->datetime('registration_deadline')->nullable()->after('end_date');
                $table->enum('location_type', ['online', 'lan'])->default('online')->after('registration_deadline');
                $table->string('location_address', 255)->nullable()->after('location_type');
                $table->json('rules')->nullable()->after('stream_link');
            });

            // Migrate data ngược lại
            $settings = DB::table('tournament_settings')->get();
            foreach ($settings as $setting) {
                DB::table('tournaments')
                    ->where('id', $setting->tournament_id)
                    ->update([
                        'prize_pool' => $setting->prize_pool,
                        'prize_distribution' => $setting->prize_distribution,
                        'max_teams' => $setting->max_teams,
                        'format' => $setting->format,
                        'competition_type' => $setting->competition_type,
                        'rules' => $setting->rules,
                    ]);
            }

            $schedules = DB::table('tournament_schedule')->get();
            foreach ($schedules as $schedule) {
                DB::table('tournaments')
                    ->where('id', $schedule->tournament_id)
                    ->update([
                        'start_date' => $schedule->start_date,
                        'end_date' => $schedule->end_date,
                        'registration_deadline' => $schedule->registration_deadline,
                        'location_type' => $schedule->location_type,
                        'location_address' => $schedule->location_address,
                    ]);
            }
        }

        // Drop các bảng mới
        Schema::dropIfExists('tournament_schedule');
        Schema::dropIfExists('tournament_settings');
    }
};
