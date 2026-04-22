<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MakeBranchCropCompositionCustomerNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE branch_crop_composition_collections MODIFY customer_code VARCHAR(255) NULL');
        DB::statement('ALTER TABLE branch_crop_composition_collections MODIFY customer_name VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE branch_crop_composition_collections MODIFY customer_code VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE branch_crop_composition_collections MODIFY customer_name VARCHAR(255) NOT NULL');
    }
}
