<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCropCatalogCategoriesTable extends Migration
{
    /**
     * Create the crop catalog categories table.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crop_catalog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Drop the crop catalog categories table.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crop_catalog_categories');
    }
}
