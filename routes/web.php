<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/test', \App\Http\Livewire\Test::class)->name('test');
Route::get('/', function () {
//    return view('welcome');
    return redirect()->route('login');
});

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {

    Route::get('/dashboard',\App\Http\Livewire\UserDashboard::class)->name('dashboard');
    Route::get('/commission-report', \App\Http\Livewire\CommissionReport::class)->name('commission-report');
//    Route::get('/list-postponed-vouchers/{area_id}/{month}/{year}', \App\Http\Livewire\ListPostponedVouchers::class)->name('list.postponed-vouchers');
//    Route::get('/list-non-paid-vouchers', \App\Http\Livewire\ListBillwise::class)->name('list.non-paid-vouchers');
    Route::get('/list-sales-profit', \App\Http\Livewire\ListSalesProfit::class)->name('list.sales-profit');
    Route::get('/list-sales-collections', \App\Http\Livewire\ListSalesCollections::class)->name('list.sales-collections');
    Route::get('/list-postponed-by-customers', \App\Http\Livewire\ListPostponedByCustomer::class)->name('list.postponed-by-customers');
    Route::get('/list-cash-statement', \App\Http\Livewire\ListCashStatement::class)->name('list.customer-cash-statement');
    Route::get('/list-products-targets', \App\Http\Livewire\ListProductsTargets::class)->name('list.products-targets');
//    Route::get('/create-product-target', \App\Http\Livewire\CreateProductTarget::class)->name('create.product-target');
    Route::match(['get','post'],'/create-product-target', \App\Http\Livewire\CreateProductTarget::class)->name('create.product-target');
    Route::get('/list-my-product-target', \App\Http\Livewire\ListMyProductTarget::class)->name('list.my-product-target');
    Route::get('/show-my-products-targets', \App\Http\Livewire\ShowMyProductsTargets::class)->name('show.my-products-targets');
    Route::get('/list-weekly-report', \App\Http\Livewire\ListWeeklyReport::class)->name('list.weekly-report');
    Route::get('/list-purchase-recommendation', \App\Http\Livewire\PurchaseRecommendation::class)->name('list.purchase-recommendation');
    Route::get('/list-distribution-calc', \App\Http\Livewire\DistributionCalc::class)->name('list.distribution-calc');


    Route::get('/new-daily-report', \App\Http\Livewire\NewDailyReport::class)->name('create.daily-report');
    Route::get('/edit-daily-report/{id}', \App\Http\Livewire\EditDailyReport::class)->name('edit.daily-report');
    Route::get('/list-daily-reports', \App\Http\Livewire\ListDailyReports::class)->name('list.daily-reports');
    Route::get('/show-daily-report/{id}', \App\Http\Livewire\ShowDailyReport::class)->name('show.daily-report');

    Route::get('/list-friends-daily-reports', \App\Http\Livewire\ListFriendsDailyReports::class)->name('list.friends-daily-reports');

    Route::get('/list-employees-daily-reports', \App\Http\Livewire\ListEmployeesDailyReports::class)->name('list.employees-daily-reports');
    Route::get('/show-employee-report/{id}/{week_date}', \App\Http\Livewire\ShowEmployeeReport::class)->name('show.employee-report');

    Route::get('/test-item-list', \App\Http\Livewire\SapItemMasterData::class)->name('test-item-list');

    /* reports */
    Route::get('/report-21', \App\Http\Livewire\Report21::class)->name('report-21');
    Route::get('/report-11', \App\Http\Livewire\Report11::class)->name('report-11');
    Route::get('/report-25', \App\Http\Livewire\Report25::class)->name('report-25');
    Route::get('/report-42', \App\Http\Livewire\Report42::class)->name('report-42');
    Route::get('/inventory-status-report', \App\Http\Livewire\InventoryStatus::class)->name('inventory-status-report');
    Route::get('/list-non-paid-vouchers', \App\Http\Livewire\ListAgingSap::class)->name('list.non-paid-vouchers');
    Route::get('/list-aging-by-customer-employee', \App\Http\Livewire\ListAgingByCustomerAndEmployee::class)->name('list.aging-by-customer-employee');
    Route::get('/profit-loss-stmt-report', \App\Http\Livewire\ProfitLossStmtReport::class)->name('profit-loss-stmt-report');

    Route::get('/sap-reports', \App\Http\Livewire\SapReports::class)->name('sap-reports');

    Route::get('/non-active-user', \App\Http\Livewire\NonActiveUser::class)->name('non-active-user');

});

Route::middleware([
    'admin',
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

//    Route::get('/dashboard',\App\Http\Livewire\UserDashboard::class)->name('dashboard');
//    Route::get('/test', \App\Http\Livewire\Test::class)->name('test');


    /* User Management */
    Route::get('/create-user', \App\Http\Livewire\CreatUser::class)->name('create.user');
    Route::get('/edit-user/{id}',\App\Http\Livewire\EditUser::class)->name('edit.user');
    Route::get('/list-users', \App\Http\Livewire\ListUser::class)->name('list.users');

    Route::get('/create-group', \App\Http\Livewire\CreateUserGroup::class)->name('create.group');
    Route::get('/list-groups', \App\Http\Livewire\ListUserGroups::class)->name('list.groups');
    Route::get('/edit-group/{id}', \App\Http\Livewire\EditUserGroups::class)->name('edit.group');

    Route::get('/settings', \App\Http\Livewire\ListSettings::class)->name('list.settings');
});
