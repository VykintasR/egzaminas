<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjektoDalyvisController;
use App\Http\Controllers\ProjektasController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\VeiklaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('pradinis');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::middleware('AdminAccess')->group(function () {
        Route::post('projektas/{projektas}/fiksuotipradzia',[ProjektasController::class, 'fiksuotiPradzia'])->name('projektas.fiksuotiPradzia');
        Route::post('projektas/{projektas}/anuliuotipradzia',[ProjektasController::class, 'anuliuotiPradzia'])->name('projektas.anuliuotiPradzia');

        Route::post('projektas/{projektas}/fiksuotipabaiga',[ProjektasController::class, 'fiksuotiPabaiga'])->name('projektas.fiksuotiPabaiga');
        Route::post('projektas/{projektas}/anuliuotipabaiga',[ProjektasController::class, 'anuliuotiPabaiga'])->name('projektas.anuliuotiPabaiga');

        Route::post('projektas/{projektas}/veikla/{veikla}/fiksuotipradzia',[VeiklaController::class, 'fiksuotiPradzia'])->name('veikla.fiksuotiPradzia');
        Route::post('projektas/{projektas}/veikla/{veikla}/anuliuotipradzia',[VeiklaController::class, 'anuliuotiPradzia'])->name('veikla.anuliuotiPradzia');

        Route::post('projektas/{projektas}/veikla/{veikla}/fiksuotipabaiga',[VeiklaController::class, 'fiksuotiPabaiga'])->name('veikla.fiksuotiPabaiga');
        Route::post('projektas/{projektas}/veikla/{veikla}/anuliuotipabaiga',[VeiklaController::class, 'anuliuotiPabaiga'])->name('veikla.anuliuotiPabaiga');

        Route::post('projektas/{projektas}/veikla/{veikla}/fiksuotibiudzeta',[VeiklaController::class, 'fiksuotiBiudzeta'])->name('veikla.fiksuotiBiudzeta');
        Route::post('projektas/{projektas}/veikla/{veikla}/anuliuotibiudzeta',[VeiklaController::class, 'anuliuotiBiudzeta'])->name('veikla.anuliuotiBiudzeta');

        Route::post('projektas/{projektas}/veikla/{veikla}/pridetiDarbuotoja',[VeiklaController::class, 'pridetiDarbuotoja'])->name('veikla.pridetiDarbuotoja');
        Route::post('projektas/{projektas}/veikla/{veikla}/ismestiDarbuotoja',[VeiklaController::class, 'ismestiDarbuotoja'])->name('veikla.ismestiDarbuotoja');
   
        Route::resource('roles', RoleController::class);
    });
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/pradzia',[HomeController::class, 'index'])->name('pagrindinis');

    Route::get('projektai/paieska',function(){
        return redirect()->route('projektai.index');
    });
    Route::post('projektai/paieska',[ProjektasController::class, 'indexFiltruotas'])->name('projektai.paieska');
    Route::get('projektai/pdf', [ProjektasController::class, 'createPDF']);

    Route::resource('projektai', ProjektasController::class)->parameters(['projektai' => 'projektas']);
   
    Route::resource('projektas.dalyviai', ProjektoDalyvisController::class, ['parameters' => [
        'dalyviai' => 'dalyvis',
        'projektas' => 'projektas'
    ]]);
    Route::resource('projektas.veiklos', VeiklaController::class, ['parameters' => [
        'veiklos' => 'veikla',
        'projektas' => 'projektas'
    ]]);
});

require __DIR__.'/auth.php';
