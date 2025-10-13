<?php

use App\Http\Controllers\BankController;
use App\Http\Controllers\CateringController;
use App\Http\Controllers\ContainerController;
use App\Http\Controllers\CostingController;
use App\Http\Controllers\InputPermitController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\OfficeExpenseController;
use App\Http\Controllers\PartiesPaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SeasonalFruitController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/get-sizes/{product_id}', function ($product_id) {
    return \App\Models\Size::where('product_id', $product_id)
        ->get(['id', 'size']);
});

// Seasonal Fruit
Route::group(['prefix' => 'seasonalfruit', 'controller' => SeasonalFruitController::class], function () {
    Route::get('/create', 'create')->name('seasonalfruit.create');
    Route::post('/store', 'store')->name('seasonalfruit.store');
    Route::get('/show', 'show')->name('seasonalfruit.show');
    Route::delete('/{id}', 'destroy')->name('seasonalfruit.destroy');
});
// Seasonal Fruit

// Costing
Route::group(['prefix' => 'costing', 'controller' => CostingController::class], function () {
    Route::get('/addCosting', 'addCosting')->name('costing.addCosting');
    Route::post('/addCosting', 'storeCosting')->name('costing.storeCosting');
    Route::get('/viewCosting', 'viewCosting')->name('costing.viewCosting');
    Route::post('/update', 'updateCosting')->name('costing.update');
    Route::delete('/{id}', 'destroy')->name('costing.destroy');
    Route::post('/lc/store', 'storeLc')->name('costing.lc.store');
    Route::get('/{id}/lc', 'getLc')->name('getLc');
});

// Bank
Route::group(['prefix' => 'banks', 'as' => 'bank.', 'controller' => BankController::class], function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/{bank}/edit', 'edit')->name('edit');
    Route::post('/{bank}/update', 'update')->name('update');
    Route::delete('/{bank}/delete', 'destroy')->name('destroy');
});
// Bank

// Transaction
Route::group(['prefix' => 'transactions', 'as' => 'transaction.', 'controller' => TransactionController::class], function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/{transaction}/edit', 'edit')->name('edit');
    Route::post('/{transaction}/update', 'update')->name('update');
    Route::delete('/{transaction}/delete', 'destroy')->name('destroy');
    Route::post('/{transaction}/status', 'updateStatus')->name('updateStatus');
    Route::get('/bank-ledgers', 'ledger')->name('ledger');
    Route::get('/bank-report', 'bankReport')->name('bank_report');
});

// Size
Route::group(['prefix' => 'products', 'as' => 'product.', 'controller' => ProductController::class], function () {
    Route::get('/size/create', 'createSize')->name('size.create');
    Route::post('/size/store', 'storeSize')->name('size.store');
    Route::get('/size/view', 'viewSize')->name('size.view');
    Route::delete('/size/{size}', 'destroySize')->name('size.destroy');
    Route::put('/size/{size}/update', 'updateSize')->name('size.update');
});

// Container
Route::group(['prefix' => 'container', 'as' => 'container.', 'controller' => ContainerController::class], function () {
    Route::get('/view', 'view')->name('view');
    Route::post('/store', 'store')->name('store');
    Route::get('/containerTbl', 'containerTbl')->name('containerTbl');
    Route::delete('/delete/{id}', 'destroy')->name('delete');
    Route::put('/update/{id}', 'update')->name('update');
    Route::get('/supplierTtLc', 'supplierTtLc')->name('supplierTtLc');
    Route::post('/ttPayment', 'ttPayment')->name('ttPayment');
    Route::post('/lcPayment', 'lcPayment')->name('lcPayment');
});

// Input Permit
Route::group(['prefix' => 'input-permit', 'as' => 'input_permit.', 'controller' => InputPermitController::class], function () {
    Route::get('/view', 'index')->name('view');
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::delete('/destroy/{id}', 'destroy')->name('destroy');
    Route::put('/update/{id}', 'update')->name('update');
});


// Office Expense
Route::group(['prefix' => 'office-expense', 'as' => 'office_expense.', 'controller' => OfficeExpenseController::class], function () {
    Route::get('/view', 'index')->name('view');
    Route::get('/cash-in-history', 'history')->name('history');
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/{id}/edit', 'edit')->name('edit');
    Route::put('/{id}/update', 'update')->name('update');
    Route::delete('/destroy/{id}', 'destroy')->name('destroy');
    Route::get('/name', 'officeExpenseName')->name('name');
    Route::post('/store-office-expense-category', 'storeOfficeExpenseCategory')->name('store_office_expense_category');
    Route::get('/view-names', 'viewOfficeExpenseName')->name('view_names');

    Route::get('/ledger', 'ledger')->name('ledger');
});

Route::group(['prefix' => 'catering', 'as' => 'catering.', 'controller' => CateringController::class], function () {
    Route::get('/index', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/{lunch}/edit', 'edit')->name('edit');
    Route::post('/{lunch}/update', 'update')->name('update');
    Route::delete('/{lunch}/delete', 'destroy')->name('delete');
});

// Investment

Route::group(['prefix' => 'investment', 'as' => 'investment.', 'controller' => InvestmentController::class], function () {
    Route::get('/index', 'index')->name('index');           // Show all investments
    Route::get('/create', 'create')->name('create');        // Show create form
    Route::post('/store', 'store')->name('store');          // Store new investment
    Route::get('/{id}/edit', 'edit')->name('edit');        // Show edit form
    Route::put('/{id}/update', 'update')->name('update'); // Update investment
    Route::delete('/{id}/delete', 'destroy')->name('delete'); // Delete investment
});

// partiesPayment
Route::group(['prefix' => 'parties-payment', 'as' => 'partiesPayment.', 'controller' => PartiesPaymentController::class], function () {
    Route::get('/index', 'index')->name('index');           // Show all payments
    Route::get('/show', 'show')->name('show');           // Show all payments
    Route::get('/create', 'create')->name('create');        // Show create form
    Route::post('/store', 'store')->name('store');          // Store new payment
    Route::get('/{id}/edit', 'edit')->name('edit');         // Show edit form
    Route::put('/{id}/update', 'update')->name('update');   // Update payment
    Route::delete('/{id}/delete', 'destroy')->name('delete'); // Delete payment
});
// routes/web.php
