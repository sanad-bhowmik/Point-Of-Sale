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

use Modules\Expense\Http\Controllers\ExpenseController;

Route::group(['middleware' => 'auth'], function () {

    //Expense Category
    Route::resource('expense-categories', 'ExpenseCategoriesController')->except('show', 'create');
    Route::resource('expense-names', 'ExpenseNameController');

    //Expense
    Route::resource('expenses', 'ExpenseController')->except('show');
    Route::get('/expenses/expense-names/{category}', [ExpenseController::class, 'getExpenseNames']);

    Route::get('/finalReport', [ExpenseController::class, 'finalReport'])->name('expense.finalReport');
    Route::post('/finalReportFilter', [ExpenseController::class, 'finalReportFilter'])->name('expense.finalReportFilter');

});
