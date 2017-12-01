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

Route::get('/', 'Instamojo@createPayment');

//User will we redirected to this route after payment with payment_id and payment_request_id
Route::get('/paymentStatus', 'Instamojo@paymentDetails');

//Instamojo will hit this url with the payment details as x-www-form-urlencoded
//Excluded this route from the VerifyCsrfToken middleware as Instamojo will not have csrf token to pass
Route::post('/paymentWebhook', 'paymentWebhook@payment');
