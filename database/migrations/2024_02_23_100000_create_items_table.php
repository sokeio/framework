<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('group_item_id')->nullable();
            $table->string('name', 255)->nullable();
            $table->string('icon', 255)->nullable();
            $table->string('color', 255)->nullable();
            $table->string('description', 400)->nullable()->default('');
            $table->longText('content')->nullable();
            $table->string('image', 255)->nullable();
            $table->timestamps();
        });
        Schema::create('group_items', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_items');
        Schema::dropIfExists('items');
    }
};
