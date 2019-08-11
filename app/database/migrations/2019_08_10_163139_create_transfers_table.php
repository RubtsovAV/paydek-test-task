<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->unsignedBigInteger('sender_id')->comment('account_id which send money');
            $table->unsignedBigInteger('receiver_id')->comment('account_id which receive money');
            $table->decimal('rate', 10, 6);
            $table->decimal('amount', 10, 2);

            $table->foreign('sender_id')->references('id')->on('accounts');
            $table->foreign('receiver_id')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfers');
    }
}
