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
        Schema::table('pembelians', function (Blueprint $table) {
            if (!Schema::hasColumn('pembelians', 'bayar')) {
                $table->integer('bayar')->after('total_price')->nullable();
            }
        });
    }
    
    
    public function down()
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropColumn('bayar');
        });
    }
    
};
