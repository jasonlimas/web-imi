<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterItemController;
use App\Http\Controllers\MasterCustomerController;
use App\Http\Controllers\SalesOrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::resource('items', MasterItemController::class);
Route::resource('customers', MasterCustomerController::class);
Route::resource('sales-orders', SalesOrderController::class);


// use Illuminate\Support\Facades\DB;

// Route::get('/test-db', function () {
//     try {
//         DB::connection()->getPdo();
//         return 'Database connection is working!';
//     } catch (\Exception $e) {
//         return 'Database connection is not working: ' . $e->getMessage();
//     }
// });
