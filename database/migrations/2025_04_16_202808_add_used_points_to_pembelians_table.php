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
            $table->integer('used_points')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropColumn('used_points');
        });
    }
    
};
