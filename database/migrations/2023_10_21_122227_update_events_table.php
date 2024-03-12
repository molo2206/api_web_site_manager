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
        Schema::table('events', function (Blueprint $table) {
            $table->foreignUuid('country_id')->nullable()->after('category_id')->constrained('countries')->onDelete('cascade')->onUpdate('cascade');
            $table->string("adresse")->nullable()->after('country_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('country_id');
            $table->dropColumn('adresse');
        });
    }
};
