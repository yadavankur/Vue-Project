<?php

Route::get('/password/reset/{token}', function () {
    return view('auth.passwords.reset');
})->name('password.reset');

Route::get('/{vue_capture?}', function () {
    return view('welcome');
});


//Auth::routes();

//Route::post('resetpassword/{token}', function ($token) {
//    return $token;
//});
//Route::get('resetpassword/{token}', function ($token) {
//    return $token;
//});

