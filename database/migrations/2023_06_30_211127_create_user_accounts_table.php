<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('account_number')->nullable();
            $table->string('server_trade')->nullable();
            $table->string('password_trading')->nullable();
            $table->string('password_investor')->nullable();
            $table->string('vps_location')->nullable();
            $table->string('key_generate'); // ( KARAKTER 50 ACAK )
            $table->integer('key_expired');
            $table->date('key_date_expired');
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
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
        Schema::dropIfExists('user_accounts');
    }
}
