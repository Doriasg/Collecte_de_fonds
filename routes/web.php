<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\paiementController;

Route::get('/', function () {
    return view('users.index');
});
Route::get('paiement', [paiementController::class,'index'])->name('paiement'); 
