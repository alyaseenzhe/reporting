<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchCropCompositionCollectionsTable extends Migration
{
    /**
     * Create the branch crop composition collections table.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_crop_composition_collections', function (Blueprint $table) {
            $table->id();
            $table->date('collection_date')->nullable();
            $table->string('branch_name')->nullable();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->string('engineer_name')->nullable();
            $table->string('customer_code')->nullable();
            $table->string('customer_name')->nullable();
            $table->unsignedInteger('farms_count')->default(0);
            $table->decimal('total_farm_area_hectares', 12, 2);
            $table->text('opportunities')->nullable();
            $table->text('challenges')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('collection_date');
            $table->index('branch_name');
            $table->index('customer_code');
        });
    }

    /**
     * Drop the branch crop composition collections table.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch_crop_composition_collections');
    }
}
