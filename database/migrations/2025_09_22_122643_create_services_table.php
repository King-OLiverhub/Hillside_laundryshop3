<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
public function up()
{
    Schema::create('services', function (Blueprint $table) {
    $table->id();
    $table->string('service_type');
    $table->integer('load_capacity_kg')->nullable();
    $table->string('per_load')->nullable();
    $table->string('services')->nullable(); 
    $table->decimal('price_per_load', 10, 2);
    $table->boolean('available')->default(true);
    $table->timestamps();
});
}



public function down()
{
Schema::dropIfExists('services');
}
}   