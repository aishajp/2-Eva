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

        Schema::create('personas', function (Blueprint $table) { 

            $table->increments('id'); 

            $table->timestamps(); 

            $table ->string('nombre', 50);  

            $table ->string('apellidos', 100); 

            $table -> integer('edad');  

        }); 

    } 

 

    /** 

     * Reverse the migrations. 

     */ 

    public function down(): void 

    { 

        Schema::dropIfExists('personas'); 

    } 
};
