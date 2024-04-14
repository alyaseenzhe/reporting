<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchIdColumnToProductTargetEmpPercents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_target_emp_percents', function (Blueprint $table) {
            //
            $table->string('branch', 15)->after('emp_percentage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_target_emp_percents', function (Blueprint $table) {
            //
        });
    }
}
