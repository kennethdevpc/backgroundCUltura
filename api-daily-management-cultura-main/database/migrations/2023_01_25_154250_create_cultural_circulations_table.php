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
        Schema::create('cultural_circulations', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('consecutive')->nullable();
            $table->string('datasheet')->nullable();
            $table->string('keyactors_circulation_alliance', 3500);
            $table->foreignId('pec_id')->references('id')->on('pecs')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('datasheet_planning_id')->references('id')->on('methodological_sheets_one')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('event_name');

            $filter_level = array_column(config('selectsDefault.filter_level'), 'value');
            $table->enum('filter_level', $filter_level);

            $table->text('description');
            $table->foreignId('nac_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('other_nac')->nullable();
            $table->integer('quantity_members');
            $table->string('public_characteristics', 3500);
            $table->foreignId('cultural_right_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            $lineaments = array_column(config('selectsDefault.lineaments'), 'value');
            $table->enum('lineament_id', $lineaments);

            $table->foreignId('orientation_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            $value = array_column(config('selectsDefault.values'), 'value');
            $table->enum('values', $value);

            $table->string('artistic_expertise', 3500);
            $table->string('participation_observations', 3500);
            $table->string('development_activity_image')->nullable();
            $table->string('evidence_participation_image')->nullable();
            $table->string('aforo_pdf')->nullable();
            $table->integer('number_attendees');

            $table->enum('last_status', ['REC', 'REV', 'ENREV', 'APRO'])->nullable();
            $table->enum('status', ['REC', 'REV', 'ENREV', 'APRO'])->default('ENREV');
            $table->enum('audited', [0, 1])->default(0);
            $table->text('reject_message')->nullable();
            $table->foreignId('created_by')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('user_review_instructor_leader_id')->nullable(); //Lider de instructor
            $table->foreign('user_review_instructor_leader_id')
                ->references('id')
                ->on('users');
            $table->unsignedBigInteger('user_review_support_follow_id')->nullable(); //Apoyo al Seguimiento y Monitoreo
            $table->foreign('user_review_support_follow_id')
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
        Schema::dropIfExists('cultural_circulations');
    }
};
