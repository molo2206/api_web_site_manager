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
        Schema::create('ask_fors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('full_name')->nullable();
            $table->string('nationalite')->nullable();
            $table->string('ministere')->nullable();
            $table->string('id_service')->nullable();
            $table->string('passport')->nullable();
            $table->string('yellow_card')->nullable();
            $table->text('motif')->nullable();
            $table->string('extrait_bank')->nullable();
            $table->enum('type', ['ask_visa', 'ask_travel'])->nullable();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ask_fors');
    }
};
