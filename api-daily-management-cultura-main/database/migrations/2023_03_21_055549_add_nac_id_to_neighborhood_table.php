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
        Schema::table('neighborhoods', function (Blueprint $table) {
            $table->unsignedBigInteger('nac_id')->nullable();
            $table->foreign('nac_id')
                ->references('id')
                ->on('nacs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('neighborhoods', function (Blueprint $table) {
            //
        });
    }
};
