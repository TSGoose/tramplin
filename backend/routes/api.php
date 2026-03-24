<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Api\Auth\AuthenticatedUserController;

Route::prefix('auth')->group(static function (): void {
    Route::get('/health', static fn (): array => ['status' => 'ok']);
    Route::get('/me', AuthenticatedUserController::class)->middleware('auth:sanctum');
});

Route::get('/health', static function (): JsonResponse {
    return response()->json([
        'status' => 'ok',
    ]);
});
