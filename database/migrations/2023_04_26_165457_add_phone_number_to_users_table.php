<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['email']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable();
            $table->unique(array('phone_number', 'email'), 'phone_number_email_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->removeColumn('phone_number');
        });
    }
};
