<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offres', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('author')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('title');
            $table->string('function');
            $table->longText('description');
            $table->date('startdate');
            $table->date('enddate');
            $table->text('file');
            $table->enum('status', ['1', '0']);
            $table->enum('deleted', ['1', '0']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offres');
    }
};
