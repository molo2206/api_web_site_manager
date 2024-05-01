<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bulletinmodeltranslation', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('bulletin_id')->constrained('bulletins')->onUpdate('cascade')->onDelete('cascade');
            $table->string('title');
            $table->longText('description');
            $table->string('year');
            $table->string('month');
            $table->date('created');
            $table->string('locale')->index();
            $table->unique(['bulletin_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bulletinmodeltranslation');
    }
};
