<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('slugs', function (Blueprint $table) {
            $table->id();
            $table->string('key', 255)->index();
            $table->integer('reference_id')->unsigned()->index();
            $table->string('reference_type', 255);
            $table->string('prefix', 120)->nullable()->default('');
            $table->string('locale', 5)->nullable()->default('en');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slugs');
    }
};
