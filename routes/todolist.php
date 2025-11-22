<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoListController;

Route::get('todolist', [TodoListController::class, 'index'])
    ->name('todolist.index');
