<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('file_entry_id');
            $table->foreign('file_entry_id')->references('id')->on('file_entries');
            $table->enum('status',['PENDING','SUCCESS','FAIL'])->default('PENDING');
            $table->unsignedBigInteger('timezone_id');
            $table->foreign('timezone_id')->references('id')->on('timezones');
            $table->dateTimeTz('scheduled_datetime');
            $table->json('response')->nullable();
            $table->json('pin')->nullable();
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
        Schema::dropIfExists('topups');
    }
}
