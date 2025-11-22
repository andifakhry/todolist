<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\TodoListController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return redirect()->route('todolist.index');
})->name('home');



require __DIR__ . '/settings.php';
require __DIR__ . '/todolist.php';
