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
            $table->string('uuid');// uuid da tabela usuario
            $table->string('rg')->nullable();
            $table->date('birthday')->nullable();
            $table->string('gender')->nullable();
            $table->string('certificate')->nullable();
            $table->integer('enabled');
            $table->json('permitions')->nullable();// armazenar uma string de json
            $table->json('without_permitions')->nullable();// armazenar uma string de json
            $table->json('packages')->nullable();
            $table->json('integrations')->nullable();
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
