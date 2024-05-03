<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('communiquestranslation', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('communiqueid')->constrained('communiques')->onUpdate('cascade')->onDelete('cascade');
            $table->string('title');
            $table->longText('description');
            $table->enum('status', ['1', '0']);
            $table->enum('deleted', ['1', '0']);
            $table->string('locale')->index();
            $table->unique(['communiqueid', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('communiquestranslation');
    }
};
