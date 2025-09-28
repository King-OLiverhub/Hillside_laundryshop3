<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateCustomersTable extends Migration
{
public function up()
{
Schema::create('customers', function (Blueprint $table) {
$table->id();
$table->string('first_name');
$table->string('middle_name')->nullable();
$table->string('last_name');
$table->string('contact')->nullable();
$table->enum('customer_type', ['walkin', 'regular'])->default('walkin');
$table->timestamps();
});
}


public function down()
{
Schema::dropIfExists('customers');
}
}