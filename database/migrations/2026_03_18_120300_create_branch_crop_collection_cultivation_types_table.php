<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchCropCollectionCultivationTypesTable extends Migration
{
    /**
     * Create the cultivation types table for branch crop collections.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_crop_collection_cultivation_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_crop_composition_collection_id');
            $table->string('cultivation_type');
            $table->string('detail_type')->nullable();
            $table->decimal('unit_count', 12, 2)->nullable();
            $table->decimal('total_area_hectares', 12, 2);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(
                ['branch_crop_composition_collection_id', 'sort_order'],
                'branch_crop_collection_cultivation_types_sort_index'
            );

            $table->foreign(
                'branch_crop_composition_collection_id',
                'bccct_collection_fk'
            )
                ->references('id')
                ->on('branch_crop_composition_collections')
                ->onDelete('cascade');
        });
    }

    /**
     * Drop the cultivation types table for branch crop collections.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch_crop_collection_cultivation_types');
    }
}
