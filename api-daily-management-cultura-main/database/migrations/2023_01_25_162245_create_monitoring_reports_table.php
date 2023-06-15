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
        Schema::create('monitoring_reports', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')
                ->references('id')
                ->on('users');
            $table->string('consecutive');
            $table->text('description');
            $table->date('date');
            $table->string('file')->nullable();

            $table->enum('status', ['REC', 'REV', 'ENREV', 'APRO'])->default('ENREV');
            $table->text('reject_message')->nullable();

            $table->unsignedBigInteger('monitoring_coordinator_id')->nullable();
            $table->foreign('monitoring_coordinator_id')
                ->references('id')
                ->on('users');


            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monitoring_reports');
    }
};
