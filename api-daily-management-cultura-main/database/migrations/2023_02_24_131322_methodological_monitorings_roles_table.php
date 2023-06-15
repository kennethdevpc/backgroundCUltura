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
        Schema::create('methodological_monitorings_roles', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('monitoring_id');
            $table->foreignId('role_id');
            $table->timestamps();

            $table->foreign('monitoring_id')
                ->references('id')
                ->on('methodological_monitorings');
            $table->foreign('role_id')
                ->references('id')
                ->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('methodological_monitorings_roles');
    }
};
