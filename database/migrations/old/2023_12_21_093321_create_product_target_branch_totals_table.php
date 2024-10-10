<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTargetBranchTotalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_target_branch_totals', function (Blueprint $table) {
            $table->id();

            $table->string('product_id', 25);
            $table->integer('month');
            $table->integer('year');
            $table->string('branch', 15);
            $table->integer('target');

            $table->unique(['product_id', 'month', 'year', 'branch']);

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
        Schema::dropIfExists('product_target_branch_totals');
    }
}
