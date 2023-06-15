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
        Schema::create('methodological_monitorings', function (Blueprint $table) {
            $table->id();
            $table->string('consecutive')->nullable();
            $table->string('datasheet')->nullable();
            $table->enum('status', ['REC', 'REV', 'ENREV', 'APRO'])->default('ENREV');

            $table->date('date_realization');
            $table->date('date_planning_ini');
            $table->date('date_planning_fin');

            $table->foreignId('cultural_right_id')->constrained()->onDelete('cascade');
            $table->foreignId('nac_id')->constrained()->onDelete('cascade');
            $table->foreignId('orientation_id')->constrained()->onDelete('cascade');

            $lineaments = array_column(config('selectsDefault.lineaments'), 'value');
            $table->enum('lineament_id', $lineaments);

            $value = array_column(config('selectsDefault.values'), 'value');
            $table->enum('value', $value);

            $table->string('comments');
            $table->string('objective_process');

            $strengthening_types = array_column(config('selectsDefault.strengthening_types'), 'value');
            $table->enum('strengthening_type', $strengthening_types);

            $table->string('development_activity_image');
            $table->string('evidence_participation_image');
            $table->string('strengthening_comments');
            $table->string('topics_to_strengthened');
            $table->enum('audited', [0, 1])->default(0);
            $table->text('reject_message')->nullable();
            // Relation creator user
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')
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
        Schema::dropIfExists('methodological_monitorings');
    }
};
