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
        Schema::table('ask_fors', function (Blueprint $table) {
            $table->date('valid_in')->after('passport')->nullable();
            $table->date('valid_out')->after('valid_in')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ask_fors', function (Blueprint $table) {
            $table->dropColumn('valid_in');
            $table->dropColumn('valid_out');
        });
    }
};
