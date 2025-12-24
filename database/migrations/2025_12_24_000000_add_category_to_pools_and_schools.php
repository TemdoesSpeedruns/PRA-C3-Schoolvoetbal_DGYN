<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add category column to pools table
        Schema::table('pools', function (Blueprint $table) {
            $table->string('category')->default('all')->comment('Leeftijdscategorie: 3/4, 5/6, 7/8, brugklas');
            $table->index('category');
        });

        // Add category column to schools table
        Schema::table('schools', function (Blueprint $table) {
            $table->string('category')->nullable()->comment('Leeftijdscategorie: 3/4, 5/6, 7/8, brugklas');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->dropColumn('category');
        });

        Schema::table('pools', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->dropColumn('category');
        });
    }
};
