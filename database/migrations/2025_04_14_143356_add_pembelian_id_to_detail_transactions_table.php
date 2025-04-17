<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('detail_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('pembelian_id')->after('id');
    
            // Kalau kamu punya foreign key ke tabel pembelians:
            $table->foreign('pembelian_id')->references('id')->on('pembelians')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('detail_transactions', function (Blueprint $table) {
            $table->dropForeign(['pembelian_id']);
            $table->dropColumn('pembelian_id');
        });
    }
};    