<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Verwijder school_id foreign key constraint eerst
        if (Schema::hasColumn('referees', 'school_id')) {
            try {
                DB::statement("ALTER TABLE referees DROP FOREIGN KEY referees_school_id_foreign");
            } catch (\Exception $e) {
                // Foreign key bestaat misschien niet
            }
            DB::statement("ALTER TABLE referees DROP COLUMN school_id");
        }
        
        // Wijzig het type enum van 'school','external' naar 'junior','senior','professional'
        DB::statement("ALTER TABLE referees MODIFY type ENUM('junior', 'senior', 'professional') DEFAULT 'junior'");
        
        // Voeg phone en experience kolommen toe
        Schema::table('referees', function (Blueprint $table) {
            if (!Schema::hasColumn('referees', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('referees', 'experience')) {
                $table->integer('experience')->nullable()->after('phone');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referees', function (Blueprint $table) {
            if (Schema::hasColumn('referees', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('referees', 'experience')) {
                $table->dropColumn('experience');
            }
        });
        
        // Zet type terug naar original
        DB::statement("ALTER TABLE referees MODIFY type ENUM('school', 'external') DEFAULT 'external'");
    }
};

