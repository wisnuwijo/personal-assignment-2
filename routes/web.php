<?php

Route::get('/', "LoginController@index");

Route::get("/login","LoginController@index");
Route::post("/login","LoginController@processLogin");
Route::post("/logout","LoginController@logout");

Route::get("/forgot-password","LoginController@forgotPassword");
Route::post("/forgot-password","LoginController@processForgotPassword");
Route::post("/reset-password","LoginController@processResetPassword");

Route::get("/loggedin","LoginController@loggedin");
