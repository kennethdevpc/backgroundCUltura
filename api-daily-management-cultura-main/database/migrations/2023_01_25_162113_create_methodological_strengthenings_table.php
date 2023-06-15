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
        Schema::create('methodological_strengthenings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')
                ->references('id')
                ->on('users');

            //Usuario que revisa
            $table->unsignedBigInteger('metho_coordinator_id')->nullable();
            $table->foreign('metho_coordinator_id')
                ->references('id')
                ->on('users');

            $table->string('consecutive')->nullable();
            $table->foreignId('nac_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('cultural_right_id')->nullable();
            $table->foreign('cultural_right_id')
                ->references('id')
                ->on('cultural_rights');

            $table->unsignedBigInteger('orientation_id')->nullable();
            $table->foreign('orientation_id')
                ->references('id')
                ->on('orientations');

            $table->date('date');

            $lineaments = array_column(config('selectsDefault.lineaments'), 'value');
            $table->enum('lineament_id', $lineaments);

            $values = array_column(config('selectsDefault.values'), 'value');
            $table->enum('value', $values);
            $table->text('comments')->nullable();

            $table->string('development_activity_image');
            $table->string('evidence_participation_image');

            $table->enum('status', ['REC', 'REV', 'ENREV', 'APRO'])->default('ENREV');
            $table->text('reject_message')->nullable();

            $table->softDeletes();
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
        Schema::dropIfExists('methodological_strengthenings');
    }
};
