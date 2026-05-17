<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddExpenseIdToExpenseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('expense_details', 'expense_id')) {
            Schema::table('expense_details', function (Blueprint $table) {
                $table->foreignId('expense_id')->nullable()->after('id');
            });
        }

        if (Schema::hasColumn('expense_details', 'expense_is')) {
            DB::table('expense_details')
                ->whereNull('expense_id')
                ->update([
                    'expense_id' => DB::raw('expense_is'),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('expense_details', 'expense_id')) {
            Schema::table('expense_details', function (Blueprint $table) {
                $table->dropColumn('expense_id');
            });
        }
    }
}
