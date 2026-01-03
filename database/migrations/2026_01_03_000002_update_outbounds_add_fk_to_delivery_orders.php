<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Rebuild outbounds to add FK to delivery_orders.no_do with restrict delete
        Schema::create('outbounds_tmp', function (Blueprint $table) {
            $table->id();
            $table->string('outgoing_id')->unique();
            $table->date('date');
            $table->string('no_do');
            $table->decimal('nett', 10, 2);
            $table->decimal('gross', 10, 2);
            $table->enum('status', ['Pending', 'Dikirim', 'Dibatalkan'])->default('Pending');
            $table->timestamps();

            $table->foreign('no_do')->references('no_do')->on('delivery_orders')->onDelete('restrict');
        });

        if (Schema::hasTable('outbounds')) {
            DB::table('outbounds_tmp')->insertUsing(
                ['id', 'outgoing_id', 'date', 'no_do', 'nett', 'gross', 'status', 'created_at', 'updated_at'],
                DB::table('outbounds')->select('id', 'outgoing_id', 'date', 'no_do', 'nett', 'gross', 'status', 'created_at', 'updated_at')
            );

            Schema::drop('outbounds');
        }

        Schema::rename('outbounds_tmp', 'outbounds');
    }

    public function down(): void
    {
        // Rebuild back without FK to delivery_orders
        Schema::create('outbounds_tmp', function (Blueprint $table) {
            $table->id();
            $table->string('outgoing_id')->unique();
            $table->date('date');
            $table->string('no_do');
            $table->decimal('nett', 10, 2);
            $table->decimal('gross', 10, 2);
            $table->enum('status', ['Pending', 'Dikirim', 'Dibatalkan'])->default('Pending');
            $table->timestamps();
        });

        if (Schema::hasTable('outbounds')) {
            DB::table('outbounds_tmp')->insertUsing(
                ['id', 'outgoing_id', 'date', 'no_do', 'nett', 'gross', 'status', 'created_at', 'updated_at'],
                DB::table('outbounds')->select('id', 'outgoing_id', 'date', 'no_do', 'nett', 'gross', 'status', 'created_at', 'updated_at')
            );

            Schema::drop('outbounds');
        }

        Schema::rename('outbounds_tmp', 'outbounds');
    }
};
