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
        // Schema::create('beneficiaries_cultural_seed_beds', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('beneficiary_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
        //     $table->foreignId('cultural_right_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();

        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('beneficiaries_cultural_seed_beds');
    }
};
