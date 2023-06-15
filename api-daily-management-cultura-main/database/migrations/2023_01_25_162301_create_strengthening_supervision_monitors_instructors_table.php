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
        Schema::create('strengthening_super_mons_insts', function (Blueprint $table) {
            $table->id();
            $table->date('revision_date');
            $table->foreignId('nac_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('supervised_user_full_name')->constrained()->onDelete('cascade');
            $table->foreign('supervised_user_full_name')
                ->references('id')
                ->on('users');

            $table->date('platform_registration_date');
            $table->string('address');
            $table->boolean('pec_reached_target');
            $table->boolean('pedagogicals_reached_target');
            $table->boolean('attendance_list');
            $table->boolean('validated_pec_time');
            $table->text('description');
            $table->text('comments');
            $table->string('development_activity_image')->nullable();
            $table->string('evidence_participation_image')->nullable();

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
            $table->string('consecutive')->nullable();
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
        Schema::dropIfExists('strengthening_supervision_monitors_instructors');
    }
};
