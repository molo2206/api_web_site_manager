<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('blogs_translation', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('blog_id')->constrained('blogs')->onUpdate('cascade')->onDelete('cascade');
            $table->string('title');
            $table->longText('description');
            $table->string('locale')->index();
            $table->unique(['blog_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs_translation');
    }
};
