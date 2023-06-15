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
        Schema::create('methodological_strengthening_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('metho_strengthening_id')->nullable();
            $table->foreign('metho_strengthening_id')
                ->references('id')
                ->on('methodological_strengthenings');
            $table->unsignedBigInteger('assistant_id')->nullable();
            $table->foreign('assistant_id')
                ->references('id')
                ->on('profiles');
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
        Schema::dropIfExists('methodological_strengthening_user');
    }
};
