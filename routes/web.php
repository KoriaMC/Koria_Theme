<?php

use Azuriom\Plugin\KoriaForum\Controllers\ForumController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ForumController::class, 'index'])->name('index');
Route::get('/c/{category:slug}', [ForumController::class, 'category'])->name('category');
Route::get('/sujet/{topic}', [ForumController::class, 'topic'])->name('topic');

Route::middleware(['auth', 'throttle:20,1'])->group(function () {
    Route::get('/c/{category:slug}/nouveau', [ForumController::class, 'create'])->name('create');
    Route::post('/c/{category:slug}/nouveau', [ForumController::class, 'store'])->name('store');
    Route::post('/sujet/{topic}/repondre', [ForumController::class, 'reply'])->name('reply');
});

Route::middleware(['auth', 'can:koriaforum.moderate'])->group(function () {
    Route::post('/sujet/{topic}/decision', [ForumController::class, 'verdict'])->name('verdict');
    Route::delete('/sujet/{topic}', [ForumController::class, 'destroyTopic'])->name('topic.destroy');
    Route::delete('/message/{post}', [ForumController::class, 'destroyPost'])->name('post.destroy');
});
