<?php

use App\Http\Controllers\AchievementsController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users/{user}/achievements', [AchievementsController::class, 'index']);

Route::get('try-achievements', function () {
    $user = App\Models\User::first();
    event(new App\Events\LessonWatched(
        App\Models\Lesson::find(1),
        $user
    ));

});