<?php

use Illuminate\Support\Facades\Route;

// Catch-all: serve the SPA shell for every web route
// The frontend is a single-page app driven by Alpine.js talking to /api/*
Route::get('/login', fn() => view('login'))->name('login');
Route::get('/{any?}', fn() => view('app'))->where('any', '.*')->name('app');
