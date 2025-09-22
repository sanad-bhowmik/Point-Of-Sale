<?php

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

use App\Models\Container;

Route::group(['middleware' => 'auth'], function () {
    //Buying Selling Report
    Route::get('/buying-selling-report', 'ReportsController@buyingSelling')
        ->name('buying-selling-report.index');
    Route::get('/get-containers-by-lc/{lc_id}', function($lc_id) {
        $containers = Container::where('lc_id', $lc_id)->get();
        return response()->json($containers);
    });

    //Stock Report
    Route::get('/stock-report', 'ReportsController@stockReport')->name('stock-report.index');
    Route::get('/stock-get-containers-by-lc/{lc_id}', 'ReportsController@getContainer')->name('stock-report.get-container');

    //Shipment Status Report
    Route::get('/shipment-status-report', 'ReportsController@shipmentStatus')
        ->name('shipment-status-report.index');

    //Cash Flow Report
    Route::get('/cash-flow-report', 'ReportsController@cashFlow')
        ->name('cash-flow-report.index');
        
    //Profit Loss Report
    Route::get('/profit-loss-report', 'ReportsController@profitLossReport')
        ->name('profit-loss-report.index');
    //Payments Report
    Route::get('/payments-report', 'ReportsController@paymentsReport')
        ->name('payments-report.index');
    //Sales Report
    Route::get('/sales-report', 'ReportsController@salesReport')
        ->name('sales-report.index');
    //Purchases Report
    Route::get('/purchases-report', 'ReportsController@purchasesReport')
        ->name('purchases-report.index');
    //Sales Return Report
    Route::get('/sales-return-report', 'ReportsController@salesReturnReport')
        ->name('sales-return-report.index');
    //Purchases Return Report
    Route::get('/purchases-return-report', 'ReportsController@purchasesReturnReport')
        ->name('purchases-return-report.index');
});
