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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();// uuid da tabela usuario
            $table->string('rg');
            $table->date('birthday');
            $table->string('gender');
            $table->string('certificate');
            $table->string('permitions');// armazenar uma string de json
            $table->string('without_permitions');// armazenar uma string de json
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
        Schema::dropIfExists('accounts');
    }
};
