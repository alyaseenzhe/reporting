<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueIndexToBranchCropCompositionCollectionsCustomerCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branch_crop_composition_collections', function (Blueprint $table) {
            $table->dropIndex(['customer_code']);

            $table->unique('customer_code', 'bcc_customer_code_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branch_crop_composition_collections', function (Blueprint $table) {
            $table->dropUnique('bcc_customer_code_unique');

            $table->index('customer_code');
        });
    }
}
