<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;

Route::get('/', function () {
    return redirect()->route('members.index');
});

Route::get('/monitoring/search/live', [MemberController::class, 'liveSearch'])->name('members.search.live');
Route::get('/monitoring/search/meta', [MemberController::class, 'getSearchMeta'])->name('members.search.meta');
Route::post('/monitoring/search/clear', [MemberController::class, 'clearSearchHistory'])->name('members.search.clear');

Route::resource('members', MemberController::class);