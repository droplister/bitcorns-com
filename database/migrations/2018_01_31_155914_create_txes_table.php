<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Some nullables for two-step process
        Schema::create('txes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('token_id')->unsigned()->index();
            $table->integer('offset')->unsigned();
            $table->string('type');
            $table->integer('block_index')->unsigned();
            $table->integer('tx_index')->unsigned()->unique();
            $table->string('tx_hash')->unique();
            $table->string('source')->nullable();
            $table->string('destination')->nullable();
            $table->bigInteger('quantity')->unsigned()->default(0);
            $table->bigInteger('fee')->unsigned()->default(0);
            $table->longText('tx_hex')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('processed_at')->nullable();
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
        Schema::dropIfExists('txes');
    }
}
