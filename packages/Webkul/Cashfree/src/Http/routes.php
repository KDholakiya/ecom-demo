<?php


Route::group(['middleware' => ['web']], function () {
    
    Route::prefix('cashfree/standard')->group(function () {
        Route::get('/redirect', 'Webkul\Cashfree\Http\Controllers\StandardController@redirect')->name('cashfree.standard.redirect');
        Route::post('/return', 'Webkul\Cashfree\Http\Controllers\StandardController@returnFromCashfree')->name('cashfree.standard.return');
    });
});


Route::post('cashfree/standard/notify', 'Webkul\Cashfree\Http\Controllers\StandardController@notify')->name('cashfree.standard.notify');
