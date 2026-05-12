<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;

Route::get('/', function () {
    return redirect()->route('members.index');
});

Route::resource('members', MemberController::class);