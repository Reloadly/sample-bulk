<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLogoToSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('systems', function (Blueprint $table) {
            $table->string('full_logo')->nullable()->after('reloadly_currency');
            $table->string('icon_logo')->nullable()->after('full_logo');
            $table->string('text_logo')->nullable()->after('icon_logo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('systems', function (Blueprint $table) {
            $table->dropColumn(['full_logo', 'icon_logo', 'text_logo']);
        });
    }
}
