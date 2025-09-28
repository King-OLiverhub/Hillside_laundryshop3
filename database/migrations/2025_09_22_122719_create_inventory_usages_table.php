<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryUsagesTable extends Migration
{
public function up()
{
Schema::create('inventory_usages', function (Blueprint $table) {
$table->id();
$table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
$table->foreignId('pos_order_item_id')->nullable()->constrained('pos_order_items')->nullOnDelete();
$table->integer('quantity_used')->default(0);
$table->text('note')->nullable();
$table->foreignId('performed_by')->nullable()->constrained('users')->nullOnDelete();
$table->timestamps();
});
}


public function down()
{
Schema::dropIfExists('inventory_usages');
}
}