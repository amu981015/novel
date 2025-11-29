<?php

use App\Http\Controllers\NovelController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [NovelController::class, 'index'])->name('novels.index');
// routes/web.php  加上這一行
Route::get('/novel/{id}', [NovelController::class, 'show'])->name('novels.show');
// routes/web.php  加這一行（我推薦這行，網址最乾淨）
Route::get('/novel/{novelId}/{chapterNum}', [NovelController::class, 'chapter'])
     ->name('novels.chapter')
     ->where(['novelId' => '[0-9]+', 'chapterNum' => '[0-9]+']);
