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
        Schema::create('methodological_sheets_one', function (Blueprint $table) {
            $table->id();
            $table->string('datasheet')->nullable();
            $table->string('consecutive')->nullable();

            $table->string('semillero_name');
            $table->date('date_ini');
            $table->date('date_fin');

            $table->foreignId('group_id')->constrained()
                ->onDelete('cascade');
            $table->foreignId('cultural_right_id')->constrained()
                ->onDelete('cascade');
            $table->foreignId('orientation_id')->constrained()
                ->onDelete('cascade');

            $lineaments = array_column(config('selectsDefault.lineaments'), 'value');
            $table->enum('lineament_id', $lineaments);

            $filter_level = array_column(config('selectsDefault.filter_level'), 'value');
            $table->enum('filter_level', $filter_level);

            $value = array_column(config('selectsDefault.values'), 'value');
            $table->enum('value', $value);
            $table->text('worked_expertise');
            $table->text('characteristics_process');
            $table->text('objective_process');
            $table->text('comments');
            $table->enum('status', ['REC', 'REV', 'ENREV', 'APRO'])->default('ENREV');
            $table->text('reject_message')->nullable();
            $table->enum('audited', [0, 1])->default(0);
            // Relation creator user
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')
                ->references('id')
                ->on('users');

            $table->unsignedBigInteger('user_review_instructor_leader_id')->nullable(); //Lider de instructor
            $table->foreign('user_review_instructor_leader_id', 'fk_ms_user_review_instructor_leader_one')
                ->references('id')
                ->on('users');

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
        Schema::dropIfExists('methodological_sheets_one');
    }
};
