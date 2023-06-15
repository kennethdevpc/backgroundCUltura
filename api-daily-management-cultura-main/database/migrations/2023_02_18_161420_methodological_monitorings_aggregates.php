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
        Schema::create('methodological_monitorings_aggregates', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('monitoring_id');
            $table->foreignId('aggregate_id');
            $table->timestamps();

            $table->foreign('monitoring_id')
                ->references('id')
                ->on('methodological_monitorings');
            $table->foreign('aggregate_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('methodological_monitorings_aggregates');
    }
};
