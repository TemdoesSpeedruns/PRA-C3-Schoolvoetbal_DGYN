<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add category column ONLY to schools table (NOT pools - keep pools simple!)
        Schema::table('schools', function (Blueprint $table) {
            if (!Schema::hasColumn('schools', 'category')) {
                $table->string('category')->nullable()->comment('Leeftijdscategorie: 3/4, 5/6, 7/8, brugklas');
                $table->index('category');
            }
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            if (Schema::hasColumn('schools', 'category')) {
                $table->dropIndex(['category']);
                $table->dropColumn('category');
            }
        });
    }
};
