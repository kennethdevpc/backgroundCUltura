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
        Schema::create('cultural_ensembles', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $filter_level = array_column(config('selectsDefault.filter_level'), 'value');
            $table->enum('filter_level', $filter_level);
            $table->foreignId('pec_id')->nullable()->constrained();
            //datasheet_planning
            $table->foreignId('datasheet_planning')->nullable()->constrained('methodological_sheets_one');
            $table->text('description');
            $table->text('assembly_characteristics');
            $table->text('objective_process');
            $table->text('public_characteristics');

            $table->foreignId('cultural_right_id')->constrained()->onDelete('cascade');
            $lineaments = array_column(config('selectsDefault.lineaments'), 'value');
            $table->enum('lineament_id', $lineaments);
            $table->foreignId('orientation_id')->constrained()->onDelete('cascade');
            $value = array_column(config('selectsDefault.values'), 'value');
            $table->enum('value', $value);
            $table->text('artistic_expertise');
            $table->string('evaluate_aspects');
            $table->text('evaluate_aspects_comments');
            /*
            Se genera string por si se almacena como ruta
            en caso contrario se debe generar de tipo blob o binary*
            */
            $table->string('aforo_pdf');
            $table->string('development_activity_image');
            $table->string('evidence_participation_image');

            $table->integer('number_attendees');
            $table->string('consecutive')->nullable();
            $table->string('datasheet')->nullable();
            $table->enum('last_status', ['REC', 'REV', 'ENREV', 'APRO'])->nullable();
            $table->enum('status', ['REC', 'REV', 'ENREV', 'APRO'])->default('ENREV');

            $table->enum('audited', [0, 1])->default(0);
            $table->text('reject_message')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')
                ->references('id')
                ->on('users');

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
        Schema::dropIfExists('cultural_ensembles');
    }
};
