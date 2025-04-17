<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('customer_id')->nullable();
            $table->decimal('total_price');
            $table->decimal('bayar')->nullable(); // tambahin jika butuh
            $table->decimal('change')->nullable(); // tambahin jika butuh
            $table->integer('points')->default(0); // tambahin jika butuh
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('pembelians');
    }
};    