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
        Schema::create('methodological_sheets_two', function (Blueprint $table) {
            $table->id();
            $table->string('datasheet')->nullable();
            $table->string('consecutive')->nullable();

            $activity_type = array_column(config('selectsDefault.activity_type'), 'value');
            $table->enum('activity_type', $activity_type);
            $table->date('date_ini');
            $table->date('date_fin');
            $table->text('keyactors_participating_community');
            $table->text('objective_process');
            $table->boolean('reached_target')->default(0);
            $table->string('sustein', 3500);
          //  $table->integer('participants_number');
            $table->string('development_activity_image');
            $table->string('evidence_participation_image');
            $table->string('aforo_pdf')->nullable();
            $table->string('number_attendees')->nullable();
            $table->text('reject_message')->nullable();
            $table->enum('status', ['REC', 'REV', 'ENREV', 'APRO'])->default('ENREV');
            $table->enum('audited', [0, 1])->default(0);

            // Relation creator user
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')
                ->references('id')
                ->on('users');

            $table->unsignedBigInteger('user_review_instructor_leader_id')->nullable(); //Lider de instructor
            $table->foreign('user_review_instructor_leader_id', 'fk_ms_user_review_instructor_leader_two')
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
        Schema::dropIfExists('methodological_sheets_two');
    }
};
