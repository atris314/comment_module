<?php

use Illuminate\Support\Facades\Route;
use Modules\Comment\app\Http\Controllers\CommentController;

Route::prefix('admin')->middleware('auth')->group(function () {
    // Comments routes
    Route::get('/comments', [CommentController::class,'index'])->name('comments.index');
    Route::post('/comment/show/{comment}', [CommentController::class,'statusShow'])->name('comments.show');
    Route::post('/comment/status/{comment}', [CommentController::class,'status'])->name('comments.status');
    Route::post('/comment/reply', [CommentController::class,'reply'])->name('comment.reply');
});


