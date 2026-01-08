<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if the unique constraint exists before trying to drop it
        $indexExists = $this->indexExists('team_invitations', 'team_invitations_team_id_user_id_status_unique');

        if ($indexExists) {
            // For MySQL, drop foreign key first
            if (DB::connection()->getDriverName() !== 'sqlite') {
                Schema::table('team_invitations', function (Blueprint $table) {
                    try {
                        $table->dropForeign(['team_id']);
                    } catch (\Exception $e) {
                        // Foreign key might not exist
                    }
                });
            }

            // Now drop the unique constraint
            Schema::table('team_invitations', function (Blueprint $table) {
                $table->dropUnique('team_invitations_team_id_user_id_status_unique');
            });

            // Re-add the foreign key for MySQL
            if (DB::connection()->getDriverName() !== 'sqlite') {
                Schema::table('team_invitations', function (Blueprint $table) {
                    $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
                });
            }
        }
    }

    public function down(): void
    {
        // Only add back if it doesn't exist
        $indexExists = $this->indexExists('team_invitations', 'team_invitations_team_id_user_id_status_unique');

        if (!$indexExists) {
            Schema::table('team_invitations', function (Blueprint $table) {
                $table->unique(['team_id', 'user_id', 'status'], 'team_invitations_team_id_user_id_status_unique');
            });
        }
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            $indexes = DB::select("PRAGMA index_list('{$table}')");
            foreach ($indexes as $index) {
                if ($index->name === $indexName) {
                    return true;
                }
            }
            return false;
        }

        if ($driver === 'mysql') {
            $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
            return count($indexes) > 0;
        }

        if ($driver === 'pgsql') {
            $indexes = DB::select("SELECT indexname FROM pg_indexes WHERE tablename = ? AND indexname = ?", [$table, $indexName]);
            return count($indexes) > 0;
        }

        return false;
    }
};
