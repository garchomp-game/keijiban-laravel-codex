<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\Horizon;

Horizon::auth(function ($request) {
    return Gate::allows('viewHorizon', [$request->user()]);
});

Route::get('/', function () {
    return view('welcome');
});
