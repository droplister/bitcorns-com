<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned()->nullable()->index();
            $table->integer('tx_id')->unsigned()->nullable()->index();
            $table->string('type')->nullable();
            $table->string('address')->unique();
            $table->string('name')->unique();
            $table->text('content');
            $table->string('image_url');
            $table->bigInteger('rewards_total')->unsigned()->default(0);
            $table->json('meta')->nullable();
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
        Schema::dropIfExists('players');
    }
}
