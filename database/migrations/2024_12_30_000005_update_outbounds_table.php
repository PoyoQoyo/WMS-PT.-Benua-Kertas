<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // For SQLite, recreate the table
        Schema::dropIfExists('outbounds');
        
        Schema::create('outbounds', function (Blueprint $table) {
            $table->id();
            $table->string('outgoing_id')->unique();
            $table->date('date');
            $table->string('sku');
            $table->integer('quantity');
            $table->string('no_do');
            $table->enum('status', ['Pending', 'Dikirim', 'Dibatalkan'])->default('Pending');
            $table->timestamps();

            $table->foreign('sku')->references('sku')->on('products')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outbounds');
        
        Schema::create('outbounds', function (Blueprint $table) {
            $table->id();
            $table->string('product_code');
            $table->string('product_name');
            $table->integer('quantity');
            $table->string('customer')->nullable();
            $table->string('destination');
            $table->timestamps();
        });
    }
};
