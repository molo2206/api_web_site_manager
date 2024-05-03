<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rapportstranslation', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('rapportid')->constrained('rapports')->onUpdate('cascade')->onDelete('cascade');
            $table->string('title');
            $table->longText('description');
            $table->enum('status', ['1', '0']);
            $table->enum('deleted', ['1', '0']);
            $table->string('locale')->index();
            $table->unique(['rapportid', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapportstranslation');
    }
};
