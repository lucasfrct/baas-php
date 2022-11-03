<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks_networks', function (Blueprint $table) {
            $table->id();
            $table->string('uid');
            $table->string('number');
            $table->string('branch');
            $table->string('operator');
            $table->integer('bank_ispb');
            $table->string('code', 6);
            $table->integer('enabled')->defaultValue(1);
            $table->unsignedBigInteger('prev_balance')->nullable();
            $table->unsignedBigInteger('balance')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banks_networks');
    }
};
