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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('uid');
            $table->bigInteger('amount');
            $table->string('payer_document', 14);
            $table->string('payer_uid', 36);
            $table->string('payer_uuid', 36);
            $table->string('payer_bank_company', 50);
            $table->string('payer_bank_code', 3);
            $table->integer('payer_bank_ispb');
            $table->string('payer_bank_branch', 4);
            $table->string('payer_bank_number', 6);
            $table->string('payer_bank_operator', 2);
            $table->string('receipient_document', 14);
            $table->string('receipient_uid', 36);
            $table->string('receipient_uuid', 36);
            $table->string('receipient_bank_company', 50);
            $table->string('receipient_bank_code', 3);
            $table->integer('receipient_bank_ispb');
            $table->string('receipient_bank_branch', 4);
            $table->string('receipient_bank_number', 6);
            $table->string('receipient_bank_operator', 2);
            $table->json('packages');
            $table->bigInteger('tax_amount');
            $table->string('status', 50);
            $table->string('type', 50);
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
        Schema::dropIfExists('transactions');
    }
};