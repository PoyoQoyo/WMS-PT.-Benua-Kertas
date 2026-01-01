<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // For SQLite, recreate the table
        Schema::dropIfExists('inbounds');
        
        Schema::create('inbounds', function (Blueprint $table) {
            $table->id();
            $table->string('incoming_id')->unique();
            $table->string('container_no');
            $table->foreignId('delivery_order_id')->constrained()->onDelete('restrict');
            $table->date('date_received');
            $table->decimal('nett', 10, 2);
            $table->decimal('gross', 10, 2);
            $table->enum('status', ['Pending', 'Diterima', 'Ditolak'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inbounds');
        
        Schema::create('inbounds', function (Blueprint $table) {
            $table->id();
            $table->string('product_code');
            $table->string('product_name');
            $table->integer('quantity');
            $table->string('supplier')->nullable();
            $table->string('location');
            $table->timestamps();
        });
    }
};
