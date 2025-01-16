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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->json('locations')->nullable();
            $table->json('data')->nullable();
            $table->string('name', 255);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id');
            $table->integer('parent_id')->default(0);
            $table->string('type', 255)->nullable()->default('link');
            $table->string('icon', 500)->nullable();
            $table->string('color', 500)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('link', 500)->nullable();
            $table->string('attribute', 500)->nullable();
            $table->string('classname', 500)->nullable();
            $table->integer('order')->default(0);
            $table->nullableMorphs('menuable');
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
        Schema::dropIfExists('menus');
        Schema::dropIfExists('menu_items');
    }
};
