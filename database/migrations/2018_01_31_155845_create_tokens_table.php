<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Lots of nullables for easy seeding
        Schema::create('tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('name')->unique();
            $table->string('long_name')->unique()->nullable();
            $table->string('issuer')->nullable();
            $table->string('description')->nullable();
            $table->text('content')->nullable();
            $table->string('image_url')->nullable();
            $table->string('thumb_url')->nullable();
            $table->bigInteger('total_issued')->unsigned()->default(0);
            $table->boolean('divisible')->default(1);
            $table->boolean('locked')->default(0);
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
        Schema::dropIfExists('tokens');
    }
}
