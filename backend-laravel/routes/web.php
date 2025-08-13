<?php

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Laravel\Horizon\Horizon;

Horizon::auth(function ($request) {
    return Gate::forUser($request->user())->allows('viewHorizon');
});

Route::get('/', function () {
    return view('welcome');
});
