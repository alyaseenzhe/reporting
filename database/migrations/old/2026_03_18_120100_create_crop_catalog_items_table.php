<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCropCatalogItemsTable extends Migration
{
    /**
     * Create the crop catalog items table.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crop_catalog_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crop_catalog_category_id');
//                ->constrained('crop_catalog_categories')
//                ->cascadeOnDelete();
            $table->string('name');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['crop_catalog_category_id', 'name'], 'crop_catalog_items_category_name_unique');
        });
    }

    /**
     * Drop the crop catalog items table.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crop_catalog_items');
    }
}
