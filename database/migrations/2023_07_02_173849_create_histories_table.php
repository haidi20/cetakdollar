<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('order', 50)->nullable();
            $table->datetime('time')->nullable();
            $table->string('type', 15)->nullable();
            $table->string('symbol', 25)->nullable();
            $table->double('price')->nullable();
            $table->double('stop_lost')->nullable();
            $table->double('take_profit')->nullable();
            $table->datetime('time_second')->nullable();
            $table->double('price_second')->nullable();
            $table->double('swap')->nullable();
            $table->double('profit')->nullable();
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
        Schema::dropIfExists('histories');
    }
}
