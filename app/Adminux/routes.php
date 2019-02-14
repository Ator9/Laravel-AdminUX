<?php

Route::namespace('\App\Adminux')->group(function() {
    Route::post('login', 'LoginController@login')->name('login');
    Route::get('login', 'LoginController@showLoginForm')->name('showLoginForm');
    Route::get('logout', 'LoginController@logout')->name('logout');

    Route::middleware('adminux')->group(function() {
        Route::get('', 'Admin\Controllers\AdminController@dashboard')->name('admin.dashboard');

        Route::resource('admin', 'Admin\Controllers\AdminController');
        Route::resource('partner', 'Partner\Controllers\PartnerController');
    });
});
