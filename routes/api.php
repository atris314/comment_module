<?php

use Illuminate\Support\Facades\Route;
use Modules\Comment\app\Http\Controllers\CommentController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('comments', CommentController::class)->names('comment');
});
