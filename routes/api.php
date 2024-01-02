<?php

use App\Http\Controllers\API\AeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PdfController;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('/email/verify/{id}/{hash}', [AuthController::class, 'email_verify']);
    Route::post('/email/verification-notification', [AuthController::class, 'verification_notification']);
});

Route::prefix('ae')->group(function () {
    Route::post('aedates', [AeController::class, 'getaedates']);
    Route::post('send', [AeController::class, 'send']);
});

Route::prefix('pdf')->group(function () {
    Route::post('getpdf', [PdfController::class, 'getPdf']);
    Route::post('getpdflist', [PdfController::class, 'getPdfList']);
    Route::post('publishpdf', [PdfController::class, 'publishPDF']);
});
