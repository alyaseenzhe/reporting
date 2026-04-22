<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToBranchCropCompositionCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branch_crop_composition_collections', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('collection_date')
                ->constrained('users')
                ->nullOnDelete();

            $table->index('user_id');
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
            $table->dropConstrainedForeignId('user_id');
        });
    }
}
