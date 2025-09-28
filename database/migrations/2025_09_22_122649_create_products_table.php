<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
public function up()
{
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('brand')->nullable();
    $table->string('unit')->nullable();
    $table->integer('quantity')->default(0);
    $table->decimal('price', 10, 2);
    $table->integer('critical_quantity')->default(0);
    $table->boolean('available')->default(true);
    $table->timestamps();
});
}


public function down()
{
Schema::dropIfExists('products');
}
}