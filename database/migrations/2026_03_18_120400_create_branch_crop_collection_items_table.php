<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchCropCollectionItemsTable extends Migration
{
    /**
     * Create the crop composition items table.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_crop_collection_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_crop_composition_collection_id');
            $table->unsignedBigInteger('crop_catalog_category_id');
            $table->unsignedBigInteger('crop_catalog_item_id');
            $table->unsignedInteger('cycles_per_year');
            $table->unsignedInteger('trees_count')->nullable();
            $table->decimal('total_area_hectares', 12, 2);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(
                ['branch_crop_composition_collection_id', 'sort_order'],
                'branch_crop_collection_items_sort_index'
            );

            $table->foreign(
                'branch_crop_composition_collection_id',
                'bcci_collection_fk'
            )
                ->references('id')
                ->on('branch_crop_composition_collections')
                ->onDelete('cascade');

            $table->foreign(
                'crop_catalog_category_id',
                'bcci_category_fk'
            )
                ->references('id')
                ->on('crop_catalog_categories')
                ->onDelete('restrict');

            $table->foreign(
                'crop_catalog_item_id',
                'bcci_item_fk'
            )
                ->references('id')
                ->on('crop_catalog_items')
                ->onDelete('restrict');
        });
    }

    /**
     * Drop the crop composition items table.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch_crop_collection_items');
    }
}
