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
        Schema::create('methodological_accompaniments', function (Blueprint $table) {
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
            //gestor o monitor relaciona a un nac
            // $table->unsignedBigInteger('user_id')->nullable();
            // $table->foreign('user_id')
            //     ->references('id')
            //     ->on('users');


            $table->foreignId('nac_id')->constrained()->onDelete('cascade');

            $table->enum('status', ['REC', 'REV', 'ENREV', 'APRO'])->default('ENREV');
            $table->text('reject_message')->nullable();

            $table->date('date');
            $table->text('others')->nullable();
            $table->text('objective_visit')->nullable();

            $aspects = array_column(config('selectsDefault.aspects'), 'value');
            $table->text('aspects');
            $table->text('aspects_comments')->nullable();
            $table->text('comments')->nullable();
            $table->string('roles');
            $table->string('development_activity_image');
            $table->string('evidence_participation_image');

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
        Schema::dropIfExists('methodological_accompaniments');
    }
};
