<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosOrdersTable extends Migration
{
public function up()
{
Schema::create('pos_orders', function (Blueprint $table) {
$table->id();
$table->string('order_number')->unique();
$table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
$table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
$table->enum('service_mode', ['drop_off', 'self_service'])->nullable();
$table->unsignedInteger('number_of_loads')->default(1);
$table->decimal('unit_price', 10, 2)->default(0);
$table->decimal('subtotal', 12, 2)->default(0);
$table->decimal('tax', 10, 2)->default(0);
$table->decimal('total', 12, 2)->default(0);
$table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
$table->timestamps();
});
}


public function down()
{
Schema::dropIfExists('pos_orders');
}
}