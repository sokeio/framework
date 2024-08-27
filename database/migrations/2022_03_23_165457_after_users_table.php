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
            $table->string('username')->nullable();
            $table->string('avatar_url')->nullable();
            $table->string('phone_number')->nullable();
            $table->boolean('is_active')->nullable();
            $table->boolean('is_banned')->nullable();
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
            $table->dropUnique(['phone_number_email_unique']);
            $table->dropUnique(['email']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->dropColumn('avatar_url');
            $table->dropColumn('phone_number');
            $table->dropColumn('is_active');
            $table->dropColumn('is_banned');
            $table->unique('email');
        });
    }
};
