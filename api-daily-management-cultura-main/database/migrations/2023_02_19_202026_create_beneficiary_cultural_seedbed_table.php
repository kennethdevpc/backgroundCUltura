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
        Schema::create('beneficiary_cultural_seedbed', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficiary_id')->constrained();

            $table->unsignedBigInteger('cultural_seedbed_id')->nullable();
            $table->foreign('cultural_seedbed_id')
                ->references('id')
                ->on('cultural_seedbeds')->constrained()->onDelete('cascade');

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
        Schema::dropIfExists('beneficiary_cultural_seedbed');
    }
};
