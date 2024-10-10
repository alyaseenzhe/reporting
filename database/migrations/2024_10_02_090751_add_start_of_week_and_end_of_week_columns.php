<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStartOfWeekAndEndOfWeekColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            //
            $table->date('start_of_week')->after('report_note');
            $table->date('end_of_week')->after('start_of_week');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            //
        });
    }
}
