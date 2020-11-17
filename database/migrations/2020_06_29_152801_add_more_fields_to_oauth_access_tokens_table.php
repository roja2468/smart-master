<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreFieldsToOauthAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oauth_access_tokens', function (Blueprint $table) {
            $table->string('device_token')->nullable();
            $table->string('imei')->nullable();
            $table->string('device_name')->nullable();
            $table->string('os_version')->nullable();
            $table->enum('device_type', ['android', 'ios']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oauth_access_tokens', function (Blueprint $table) {
            $table->dropColumn('device_token');
            $table->dropColumn('imei');
            $table->dropColumn('device_name');
            $table->dropColumn('os_version');
            $table->dropColumn('device_type');
        });
    }
}
