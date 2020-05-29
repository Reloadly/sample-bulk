<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('systems', function (Blueprint $table) {
            $table->id();
            $table->string('reloadly_api_key')->nullable();
            $table->string('reloadly_api_secret')->nullable();
            $table->longText('reloadly_api_token')->nullable();
            $table->enum('reloadly_api_mode',['LIVE','TEST'])->default('TEST');
            $table->string('reloadly_currency')->nullable();
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
        Schema::dropIfExists('systems');
    }
}
