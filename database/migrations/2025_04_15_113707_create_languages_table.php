<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateLanguagesTable extends Migration
{
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // uz, en, ru 
             $table->string('name'); // O’zbek tili, Rus tili
             $table->string('prefix')->unique(); // uz, ru, en
             $table->boolean('is_active')->default(true); // aktiv yoki yo‘q    
             $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('languages');
    }
}
