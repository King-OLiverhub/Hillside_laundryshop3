<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddonsTable extends Migration
{
public function up()
{
Schema::create('addons', function (Blueprint $table) {
$table->id();
$table->string('name');
$table->text('description')->nullable();
$table->decimal('unit_price', 10, 2)->default(0);
$table->boolean('available')->default(true);
$table->timestamps();
});
}


public function down()
{
Schema::dropIfExists('addons');
}
}