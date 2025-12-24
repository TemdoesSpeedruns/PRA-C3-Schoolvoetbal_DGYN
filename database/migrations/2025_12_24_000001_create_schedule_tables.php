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
        // Tabel voor speelvelden
        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // "Veld A", "Veld B", etc
            $table->enum('type', ['voetbal', 'lijnbal'])->default('voetbal');
            $table->integer('capacity')->default(20); // Max aantal spelers
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabel voor scheidsrechters (kunnen scholen of externe personen zijn)
        Schema::create('referees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->enum('type', ['school', 'external'])->default('external');
            $table->foreignId('school_id')->nullable()->constrained('schools')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Update matches tabel om veld en scheidsrechter op te nemen
        Schema::table('matches', function (Blueprint $table) {
            // Voeg veld en scheidsrechter toe
            $table->foreignId('field_id')->nullable()->after('tournament_id')->constrained('fields')->onDelete('set null');
            $table->foreignId('referee_id')->nullable()->after('field_id')->constrained('referees')->onDelete('set null');
            
            // Hernoem/zorg dat we scheduled_time hebben
            $table->dateTime('scheduled_time')->nullable()->after('match_date')->comment('Geplande starttijd');
            $table->integer('duration_minutes')->default(15)->after('scheduled_time')->comment('Duur wedstrijd in minuten');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropForeignIdFor('fields');
            $table->dropForeignIdFor('referees');
            $table->dropColumn(['field_id', 'referee_id', 'scheduled_time', 'duration_minutes']);
        });

        Schema::dropIfExists('referees');
        Schema::dropIfExists('fields');
    }
};
