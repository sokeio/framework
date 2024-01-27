<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class  extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::create('menu_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->longText('locations')->nullable();
        });
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->default(0);
            $table->integer('menu_location_id');
            $table->string('icon', 500)->nullable();
            $table->string('name', 255);
            $table->string('link', 500)->nullable();
            $table->string('attr_name', 500)->nullable();
            $table->string('class_name', 500)->nullable();
            $table->longText('data')->nullable();
            $table->integer('order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_locations');
        Schema::dropIfExists('menus');
    }
};
