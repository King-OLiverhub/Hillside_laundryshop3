<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosOrderItemsTable extends Migration
{
public function up()
{
Schema::create('pos_order_items', function (Blueprint $table) {
$table->id();
$table->foreignId('pos_order_id')->constrained('pos_orders')->cascadeOnDelete();
$table->string('item_name'); 
$table->unsignedInteger('quantity')->default(1);
$table->decimal('unit_price', 10, 2)->default(0);
$table->decimal('amount', 12, 2)->default(0);
$table->timestamps();
});
}


public function down()
{
Schema::dropIfExists('pos_order_items');
}
}