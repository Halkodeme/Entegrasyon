<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Payment\PaymentController;

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

Route::post('get-pos',[PaymentController::class,'getPos'])->name('payment.get-pos');
Route::get('',[PaymentController::class,'index'])->name('payment.index');
Route::get('payment-3d',[PaymentController::class,'payment3dView'])->name('payment-3d.index');
Route::get('payment-2d',[PaymentController::class,'payment2dView'])->name('payment-2d.index');
Route::get('refund-view',[PaymentController::class,'refundView'])->name('refund-view.index');
Route::get('response-refund',[PaymentController::class,'responseRefund'])->name('response-refund.index');
Route::get('check-status-view',[PaymentController::class,'checkStatusView'])->name('check-status.index');
Route::get('response-check-status',[PaymentController::class,'checkStatusResponse'])->name('check-status-response');
Route::get('purchase-link-view',[PaymentController::class,'purchaseLinkView'])->name('purchase-link.index');
Route::get('confirm-payment-view',[PaymentController::class,'confirmPaymentView'])->name('confirm-payment.index');
Route::get('response-confirm-payment',[PaymentController::class,'confirmPaymentResponse'])->name('confirm-payment-response');




Route::post('process-3d',[PaymentController::class,'processPayment3d'])->name('process-3d');
Route::post('process-2d',[PaymentController::class,'processPayment2d'])->name('process-2d');
Route::post('purchase-link',[PaymentController::class,'purchaseLink'])->name('purchase-link');
Route::post('refund',[PaymentController::class,'refund'])->name('refund');
Route::post('confirm-payment',[PaymentController::class,'confirmPayment'])->name('confirm-payment');
Route::post('check-status',[PaymentController::class,'checkStatus'])->name('check-status');
Route::get('get-token',[PaymentController::class,'getToken'])->name('get-token');
Route::get('success',[PaymentController::class,'success'])->name('success');
Route::get('error',[PaymentController::class,'error'])->name('error');
Route::get('parameters',[PaymentController::class,'parameters'])->name('parameters');

