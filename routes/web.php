<?php


/* --------------------- Common/User Routes START -------------------------------- */

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/* --------------------- Common/User Routes END -------------------------------- */

/* ----------------------- Admin Routes START -------------------------------- */

Route::prefix('/admin')->name('admin.')->namespace('Admin')->group(function(){
    
    /**
     * Admin Auth Route(s)
     */
    Route::namespace('Auth')->group(function(){
        
        //Login Routes
        Route::get('/login','LoginController@showLoginForm')->name('login');
        Route::post('/login','LoginController@login');
        Route::post('/logout','LoginController@logout')->name('logout');

        //Register Routes
        Route::get('/register','RegisterController@showRegistrationForm')->name('register');
        Route::post('/register','RegisterController@register');

        //Forgot Password Routes
        Route::get('/password/reset','ForgotPasswordController@showLoginRequestForm')->name('password.request');
        Route::post('/password/email','ForgotPasswordController@sendResetLinkEmail')->name('password.email');

        //Reset Password Routes
        Route::get('/password/reset/{token}','ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('/password/reset','ResetPasswordController@reset');

    });

    Route::get('/dashboard','HomeController@index')->name('home');

    Route::get('/new',function(){
        return 'hello admin route!';
    })->middleware('auth:admin');

    //Put all of your admin routes here...

});

/* ----------------------- Admin Routes END -------------------------------- */
