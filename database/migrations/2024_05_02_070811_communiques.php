<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('communiques', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('author')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->text('file');
            $table->date('created');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('communiques');
    }

};
