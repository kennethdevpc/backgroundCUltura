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
        Schema::create('binnacle_cultural_show', function (Blueprint $table) {
            $table->id();
            $table->string('consecutive')->nullable();

            $table->date('date_range');

            $table->text('activity');
            $table->text('expertise');
            $table->text('artistic_participation');
            $table->boolean('reached_target');
            $table->text('sustein');

            $table->string('development_activity_image')->nullable();
            $table->string('evidence_participation_image')->nullable();
            $table->string('aforo_pdf')->nullable();
            $table->integer('number_attendees');

            $table->text('reject_message')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')
                ->references('id')
                ->on('users')->constrained()->onDelete('cascade');

            $table->enum('status', ['REC', 'REV', 'ENREV', 'APRO'])->default('ENREV');
            $table->enum('last_status', ['REC', 'REV', 'ENREV', 'APRO'])->nullable();

            $table->enum('audited', [0, 1])->default(0);

            $table->unsignedBigInteger('user_review_support_follow_id')->nullable();
            $table->foreign('user_review_support_follow_id')
                ->references('id')
                ->on('users');

            $table->unsignedBigInteger('user_review_ambassador_leader_id')->nullable();
            $table->foreign('user_review_ambassador_leader_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

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
        Schema::dropIfExists('binnacle_cultural_show');
    }
};
