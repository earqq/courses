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

Route::get('add-achievements', function () {
    $user = App\Models\User::first();
    for($i = 1; $i <=17; $i++) {
        $lesson = App\Models\Lesson::find($i);
        $user->watched()->attach($lesson,['watched' => true]);
        event(new App\Events\LessonWatched(
            $lesson,
            $user
        ));
    }
    
    $comment = new App\Models\Comment();
    $comment->body = 'This is a comment';
    $comment->user_id = $user->id;
    $comment->save();
    event(new App\Events\CommentWritten(
        $comment,
    ));
});