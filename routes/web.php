<?php

use App\Helpers\QueueHelper;
use App\Http\Controllers\AnnouncementController;
use Illuminate\Support\Facades\Route;


Route::get('/', [AnnouncementController::class, 'index'])->name('index');
Route::post('create', [AnnouncementController::class, 'create'])->name('create');
Route::get('/fetch', [AnnouncementController::class, 'fetch'])->name('fetch');
Route::get('/detail', [AnnouncementController::class, 'detail'])->name('detail');

Route::get('/resend_failed_job', [QueueHelper::class, 'sendFailedJobs'])->name('resend_failed_job');
Route::get('/mail-count', [QueueHelper::class, 'mailCount'])->name('mail_count');

