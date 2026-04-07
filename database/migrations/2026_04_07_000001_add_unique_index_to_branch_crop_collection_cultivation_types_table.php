<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueIndexToBranchCropCollectionCultivationTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branch_crop_collection_cultivation_types', function (Blueprint $table) {
            $table->unique(
                [
                    'branch_crop_composition_collection_id',
                    'agri_type_id',
                    'agri_detail_id',
                ],
                'bccct_collection_agri_type_detail_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branch_crop_collection_cultivation_types', function (Blueprint $table) {
            $table->dropUnique('bccct_collection_agri_type_detail_unique');
        });
    }
}
