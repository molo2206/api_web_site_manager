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
        Schema::create('setting_translations', function (Blueprint $table) {

            $table->id();
            $table->foreignId('settings_id')->constrained('settings')->onUpdate('cascade')->onDelete('cascade');
            $table->longText('about_us')->nullable();
            $table->longText('mission')->nullable();
            $table->longText('vision')->nullable();
            $table->longText('history')->nullable();
            $table->longText('values')->nullable();
            $table->string('locale')->index();
            $table->unique(['settings_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_translations');
    }
};
