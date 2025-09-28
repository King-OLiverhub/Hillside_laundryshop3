<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixDatabaseSchema extends Migration
{
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('services', 'max_loads')) {
                $table->dropColumn('max_loads');
            }
            if (Schema::hasColumn('services', 'service_mode')) {
                $table->dropColumn('service_mode');
            }
            
            if (!Schema::hasColumn('services', 'per_load')) {
                $table->string('per_load')->nullable()->after('load_capacity_kg');
            }
            if (!Schema::hasColumn('services', 'services')) {
                $table->string('services')->nullable()->after('per_load');
            }
        });

        Schema::table('pos_orders', function (Blueprint $table) {
            $table->enum('service_mode', ['drop_off', 'self_service', 'pickup', 'delivery', 'walk_in'])
                  ->nullable()
                  ->change();
        });

        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'available')) {
                $table->boolean('available')->default(true)->after('customer_type');
            }
        });
    }

    public function down()
    {
        // Revert changes if needed
    }
}