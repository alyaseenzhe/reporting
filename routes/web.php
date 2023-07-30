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
    Route::get('/list-non-paid-vouchers', \App\Http\Livewire\ListBillwise::class)->name('list.non-paid-vouchers');
    Route::get('/list-sales-profit', \App\Http\Livewire\ListSalesProfit::class)->name('list.sales-profit');
    Route::get('/list-sales-collections', \App\Http\Livewire\ListSalesCollections::class)->name('list.sales-collections');

});

Route::middleware([
    'admin',
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
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
});
