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
        Schema::table('matches', function (Blueprint $table) {
            // Voeg referee_id toe als foreign key naar referees tabel (als die nog niet bestaat)
            if (!Schema::hasColumn('matches', 'referee_id')) {
                $table->foreignId('referee_id')->nullable()->constrained('referees')->onDelete('set null');
            }
            // Voeg field_id toe voor speelveld (als die nog niet bestaat)
            if (!Schema::hasColumn('matches', 'field_id')) {
                $table->foreignId('field_id')->nullable()->constrained('fields')->onDelete('set null');
            }
            // Voeg duration toe voor speelduur (als die nog niet bestaat)
            if (!Schema::hasColumn('matches', 'duration_minutes')) {
                $table->integer('duration_minutes')->nullable();
            }
            // Voeg scheduled_time toe (als die nog niet bestaat)
            if (!Schema::hasColumn('matches', 'scheduled_time')) {
                $table->dateTime('scheduled_time')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropForeignIdFor('referee_id');
            $table->dropForeignIdFor('field_id');
            $table->dropColumn('duration_minutes');
            $table->dropColumn('scheduled_time');
        });
    }
};
