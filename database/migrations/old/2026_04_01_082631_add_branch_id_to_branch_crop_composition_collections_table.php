<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchIdToBranchCropCompositionCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branch_crop_composition_collections', function (Blueprint $table) {
            $table->foreignId('branch_id')
                ->nullable()
                ->after('collection_date')
                ->constrained('branches')
                ->nullOnDelete();

            $table->index('branch_id');
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
            $table->dropConstrainedForeignId('branch_id');
        });
    }
}
