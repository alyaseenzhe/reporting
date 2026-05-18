<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToBranchCropCompositionCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branch_crop_composition_collections', function (Blueprint $table) {
//            $table->string('lead_name')->nullable();
//            $table->string('lead_phone')->nullable();
//            $table->string('lead_email')->nullable();
//            $table->string('lead_email')->nullable();
            $table->string('type')->nullable();
            $table->foreignId('lead_id')->nullable();
            $table->longText('companies')->nullable();
            $table->foreignId('created_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branch_crop_composition_collection', function (Blueprint $table) {
            //
        });
    }
}
