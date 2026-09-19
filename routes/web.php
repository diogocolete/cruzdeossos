<?php

use App\Http\Controllers\FichaPdfController;
use App\Http\Controllers\SiteController;
use Filament\Http\Middleware\Authenticate as FilamentAuthenticate;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/galeria', [SiteController::class, 'galeria'])->name('galeria');
Route::get('/noticias/{slug}', [SiteController::class, 'noticiaShow'])->name('noticias.show');
Route::get('/nossa-sede', [SiteController::class, 'nossaSede'])->name('nossa-sede');
Route::get('/posts', [SiteController::class, 'posts'])->name('posts.index');
Route::get('/posts/{slug}', [SiteController::class, 'postShow'])->name('posts.show');
Route::get('/integrantes/{slug}', [SiteController::class, 'integranteShow'])->name('integrantes.show');
Route::get('/junte-se', [SiteController::class, 'junteSe'])->name('junte-se');
Route::post('/junte-se', [SiteController::class, 'inscricao'])
    ->name('junte-se.store')
    ->middleware('throttle:5,1');
Route::get('/junte-se/etapa-2/{token}', [SiteController::class, 'junteSeEtapa2'])->name('junte-se.etapa2');
Route::post('/junte-se/etapa-2/{token}', [SiteController::class, 'inscricaoEtapa2'])
    ->name('junte-se.etapa2.store')
    ->middleware('throttle:5,1');

// Área privada — PDF da ficha (exige login do painel + permissão)
Route::middleware([FilamentAuthenticate::class])->group(function () {
    Route::get('/admin/fichas/{ficha}/pdf', [FichaPdfController::class, 'atual'])->name('admin.ficha.pdf');
    Route::get('/admin/ficha-revisoes/{revisao}/pdf', [FichaPdfController::class, 'revisao'])->name('admin.ficha.revisao.pdf');
});
