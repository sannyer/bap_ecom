<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DueDateCalculatorController;

Route::get('/due-date', [DueDateCalculatorController::class, 'calculateDueDate']);