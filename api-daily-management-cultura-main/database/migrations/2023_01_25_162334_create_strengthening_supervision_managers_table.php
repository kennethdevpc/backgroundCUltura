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
        Schema::create('strengthening_super_mangs', function (Blueprint $table) {
            $table->id();
            // CAMPOS FORMULARIO
            $table->string('consecutive')->nullable();
            $table->date('revision_date');
            $table->string('address')->nullable();
            $table->enum('methodological_instruction_reached_target', [1,0])->default(0);
            $table->integer('frequency');
            $table->integer('binnacle_registered_plataform');
            $table->text('description');
            $table->time('start_time');
            $table->time('final_time');
            $table->text('comments');
            $table->string('development_activity_image')->nullable();
            $table->string('evidence_participation_image')->nullable();
            /* RELACIONES CON OTRAS TABLAS */
            /* NACS */
            $table->unsignedBigInteger('nac_id')->nullable();
            $table->foreign('nac_id')
                ->references('id')
                ->on('nacs');
            /* ROL */
            $table->unsignedBigInteger('user_associate_id')->nullable();
            $table->foreign('user_associate_id')
                ->references('id')
                ->on('users');
            /* USUARIO */

            /* METHODOLOGICALINSTRUCTIONS */
            $table->unsignedBigInteger('methodological_instruction_id')->nullable();
            $table->foreign('methodological_instruction_id')
                ->references('id')
                ->on('methodological_instructions');
            //Usuario creador
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')
                ->references('id')
                ->on('users');

            //Usuario que revisa
            $table->unsignedBigInteger('super_coordinator_id')->nullable();
            $table->foreign('super_coordinator_id')
                ->references('id')
                ->on('users');
            $table->enum('status', ['REC', 'REV', 'ENREV', 'APRO'])->default('ENREV');
            $table->text('reject_message')->nullable();

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
        Schema::dropIfExists('strengthening_super_mangs');
    }
};
