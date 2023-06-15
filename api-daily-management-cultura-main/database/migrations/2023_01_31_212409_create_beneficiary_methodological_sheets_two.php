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
        Schema::create('beneficiary_methodological_sheets_two', function (Blueprint $table) {
            $table->id();
           /*  $table->foreignId('methodological_sheets_two_id')->constrained()
                ->onDelete('cascade'); */
            $table->foreignId('beneficiary_id')->constrained();

            $table->unsignedBigInteger('m_s_two_id')->nullable();
            $table->foreign('m_s_two_id')
                ->references('id')
                ->on('methodological_sheets_two')->constrained()->onDelete('cascade');

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
        Schema::dropIfExists('beneficiary_methodological_sheets_two');
    }
};
