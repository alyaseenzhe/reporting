<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddEngineerNameToBranchCropCompositionCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branch_crop_composition_collections', function (Blueprint $table) {
            $table->string('engineer_name')->nullable()->after('engineer_id');
        });

        Schema::table('branch_crop_composition_collections', function (Blueprint $table) {
            $table->dropForeign(['engineer_id']);
        });

        DB::statement('ALTER TABLE branch_crop_composition_collections MODIFY engineer_id BIGINT UNSIGNED NULL');

        Schema::table('branch_crop_composition_collections', function (Blueprint $table) {
            $table->foreign('engineer_id')->references('id')->on('users')->restrictOnDelete();
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
            $table->dropForeign(['engineer_id']);
        });

        DB::statement('ALTER TABLE branch_crop_composition_collections MODIFY engineer_id BIGINT UNSIGNED NOT NULL');

        Schema::table('branch_crop_composition_collections', function (Blueprint $table) {
            $table->foreign('engineer_id')->references('id')->on('users')->restrictOnDelete();
            $table->dropColumn('engineer_name');
        });
    }
}
