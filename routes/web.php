<?php

use App\Http\Controllers\FiscalCrudController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('fiscal')->name('fiscal.')->group(function () {
    Route::get('/', [FiscalCrudController::class, 'index'])->name('index');
    Route::get('/crear', [FiscalCrudController::class, 'create'])->name('create');
    Route::post('/', [FiscalCrudController::class, 'store'])->name('store');
    Route::get('/{empresa}', [FiscalCrudController::class, 'show'])->name('show');
    Route::get('/{empresa}/editar', [FiscalCrudController::class, 'edit'])->name('edit');
    Route::put('/{empresa}', [FiscalCrudController::class, 'update'])->name('update');
    Route::delete('/{empresa}', [FiscalCrudController::class, 'destroy'])->name('destroy');
});
