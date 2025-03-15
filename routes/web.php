<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\XeroController;
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

Route::get('/', function () {
    return view('welcome');
});
Route::post('/xero/webhook', 'XeroWebhookController');


Route::get('/xero/auth', [XeroController::class, 'redirectToXero'])->name('xero.auth');
Route::get('/xero/callback', [XeroController::class, 'handleCallback'])->name('xero.callback');
Route::get('/xero/invoice', [XeroController::class, 'createInvoice'])->name('xero.createInvoice');
Route::get('/xero/bill', [XeroController::class, 'createBill'])->name('xero.createBill');
