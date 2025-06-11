<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitEmpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visit_emps', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('visit_id')->unsigned()->nullable();
            $table->foreign('visit_id')->references('id')->on('visits')->onUpdate('cascade')->onDelete('set null');

            $table->text('type');

            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');

            $table->longText('reviews')->nullable();

            $table->unique(['visit_id', 'user_id']);

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
        Schema::dropIfExists('visit_emps');
    }
}
