<?php

use App\Http\Controllers\CostingController;
use App\Http\Controllers\SeasonalFruitController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');

Auth::routes(['register' => false]);

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'HomeController@index')
        ->name('home');

    Route::get('/sales-purchases/chart-data', 'HomeController@salesPurchasesChart')
        ->name('sales-purchases.chart');

    Route::get('/current-month/chart-data', 'HomeController@currentMonthChart')
        ->name('current-month.chart');

    Route::get('/payment-flow/chart-data', 'HomeController@paymentChart')
        ->name('payment-flow.chart');
});


// Seasonal Fruit
Route::get('/seasonalfruit/create', [SeasonalFruitController::class, 'create'])->name('seasonalfruit.create');
Route::post('/seasonalfruit/store', [SeasonalFruitController::class, 'store'])->name('seasonalfruit.store');
Route::get('/seasonalfruit/show', [SeasonalFruitController::class, 'show'])->name('seasonalfruit.show');
Route::delete('/seasonalfruit/{id}', [App\Http\Controllers\SeasonalFruitController::class, 'destroy'])
    ->name('seasonalfruit.destroy');
// Seasonal Fruit

// Costing
Route::get('/costing/addCosting', [CostingController::class, 'addCosting'])->name('costing.addCosting');
Route::post('/costing/addCosting', [CostingController::class, 'storeCosting'])->name('costing.storeCosting');
Route::get('/costing/viewCosting', [CostingController::class, 'viewCosting'])->name('costing.viewCosting');
Route::delete('/costing/{id}', [\App\Http\Controllers\CostingController::class, 'destroy'])->name('costing.destroy');

// Costing
