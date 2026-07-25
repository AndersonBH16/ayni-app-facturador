<?php

use App\Livewire\Market\Catalogo;
use App\Livewire\Market\{Registro, Login as MarketLogin};
use App\Livewire\Market\VerProducto;
use Illuminate\Support\Facades\Route;

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

Route::get('/market', Catalogo::class)->name('market.catalogo');

Route::middleware('guest:market')->group(function () {
    Route::get('/market/registro', Registro::class)->name('market.registro');
    Route::get('/market/login', MarketLogin::class)->name('market.login');
});

Route::post('/market/logout', function () {
    auth('market')->logout();
    request()->session()->invalidate();
    return redirect()->route('market.catalogo');
})->middleware('auth:market')->name('market.logout');

Route::get('/market/producto/{producto}', VerProducto::class)->name('market.producto');

Route::middleware('auth:web')->post('/market/entrar-como-maestro', function () {
    if (! auth('web')->user()->hasRole('superadmin')) {
        abort(403);
    }

    $maestro = \App\Models\MarketUsuario::where('email', 'maestro@demo.test')->firstOrFail();
    auth('market')->login($maestro);

    return redirect()->route('market.catalogo');
})->name('market.entrar-maestro');
