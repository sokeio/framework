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
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->json('widgets')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_private')->default(true);
            $table->bigInteger('user_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('dashboard_roles', function (Blueprint $table) {
            $table->integer('dashboard_id');
            $table->integer('role_id');
            $table->primary(['dashboard_id', 'role_id']);
        });
        Schema::create('dashboard_users', function (Blueprint $table) {
            $table->integer('dashboard_id');
            $table->integer('user_id');
            $table->primary(['dashboard_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dashboards');
        Schema::dropIfExists('dashboard_roles');
        Schema::dropIfExists('dashboard_users');
    }
};
