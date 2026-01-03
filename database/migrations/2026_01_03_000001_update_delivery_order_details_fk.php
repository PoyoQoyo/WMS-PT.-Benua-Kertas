<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Rebuild table to change FK to cascade delete (SQLite friendly)
        Schema::create('delivery_order_details_tmp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_order_id')->constrained()->onDelete('cascade');
            $table->string('sku');
            $table->integer('quantity');
            $table->string('unit');
            $table->timestamps();

            $table->foreign('sku')->references('sku')->on('products')->onDelete('cascade');
        });

        // Copy data from the existing table if it exists
        if (Schema::hasTable('delivery_order_details')) {
            DB::table('delivery_order_details_tmp')->insertUsing(
                ['id', 'delivery_order_id', 'sku', 'quantity', 'unit', 'created_at', 'updated_at'],
                DB::table('delivery_order_details')->select('id', 'delivery_order_id', 'sku', 'quantity', 'unit', 'created_at', 'updated_at')
            );

            Schema::drop('delivery_order_details');
        }

        Schema::rename('delivery_order_details_tmp', 'delivery_order_details');
    }

    public function down(): void
    {
        // Rebuild back to restrict behavior
        Schema::create('delivery_order_details_tmp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_order_id')->constrained()->onDelete('cascade');
            $table->string('sku');
            $table->integer('quantity');
            $table->string('unit');
            $table->timestamps();

            $table->foreign('sku')->references('sku')->on('products')->onDelete('restrict');
        });

        if (Schema::hasTable('delivery_order_details')) {
            DB::table('delivery_order_details_tmp')->insertUsing(
                ['id', 'delivery_order_id', 'sku', 'quantity', 'unit', 'created_at', 'updated_at'],
                DB::table('delivery_order_details')->select('id', 'delivery_order_id', 'sku', 'quantity', 'unit', 'created_at', 'updated_at')
            );

            Schema::drop('delivery_order_details');
        }

        Schema::rename('delivery_order_details_tmp', 'delivery_order_details');
    }
};
