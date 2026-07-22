<?php

use App\Livewire\Storefront\Catalogo;
use Illuminate\Support\Facades\Route;
use App\Livewire\Storefront\{Registro, Login as PortalLogin};
use App\Livewire\Storefront\VerProducto;

Route::get('/', function () {
    return redirect()->route(auth()->check() ? 'dashboard' : 'login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/tienda', Catalogo::class)->name('storefront.catalogo');

Route::middleware('guest:portal')->group(function () {
    Route::get('/tienda/registro', Registro::class)->name('portal.registro');
    Route::get('/tienda/login', PortalLogin::class)->name('portal.login');
});

Route::post('/tienda/logout', function () {
    auth('portal')->logout();
    request()->session()->invalidate();
    return redirect()->route('storefront.catalogo');
})->middleware('auth:portal')->name('portal.logout');

Route::get('/tienda/producto/{producto}', VerProducto::class)->name('storefront.producto');
