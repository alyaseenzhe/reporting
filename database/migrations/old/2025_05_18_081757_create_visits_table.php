<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();

            $table->text('title');
            $table->bigInteger('requester_id')->unsigned()->nullable();
            $table->foreign('requester_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');

            $table->dateTime('start');
            $table->dateTime('end');

            $table->longText('reason');
            $table->integer('branch');

            $table->bigInteger('recipient_id')->unsigned()->nullable();
            $table->foreign('recipient_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');

            $table->char('status')->default('0'); // 0 = pending , 1 approved, 2 rejected

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
        Schema::dropIfExists('visits');
    }
}
