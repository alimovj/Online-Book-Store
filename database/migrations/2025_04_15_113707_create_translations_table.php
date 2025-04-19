<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->string('locale');
            $table->string('key');
            $table->text('value');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['key', 'locale']);
        }); 
    }

    public function down()
    {
        Schema::dropIfExists('translations');
    }
}
