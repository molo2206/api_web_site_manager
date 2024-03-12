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
        Schema::table('users', function (Blueprint $table) {
            $table->string('country')->after('type')->nullable();
            $table->string('town')->after('country')->nullable();
            $table->string('chucrh_name')->after('city_id')->nullable();
            $table->enum('contribution', ['annual', 'monthly'])->after('chucrh_name')->nullable();
            $table->float('amount')->after('contribution')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('country');
            $table->dropColumn('town');
            $table->dropColumn('chucrh_name');
            $table->dropColumn('contribution');
            $table->dropColumn('amount');
        });
    }
};
