<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Applicant\ApplicantProfileController;
use App\Http\Controllers\Api\Applicant\UpdateApplicantProfileController;
use App\Http\Controllers\Api\Auth\AuthenticatedUserController;
use App\Http\Controllers\Api\Auth\CuratorLoginController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterApplicantController;
use App\Http\Controllers\Api\Auth\RegisterEmployerController;
use App\Http\Controllers\Api\Opportunity\OpportunityIndexController;
use App\Http\Controllers\Api\Opportunity\OpportunityShowController;
use App\Http\Controllers\Api\Tag\TagIndexController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

Route::get('/health', static function (): JsonResponse {
    return response()->json([
        'status' => 'ok',
    ]);
});

Route::prefix('auth')->group(static function (): void {
    Route::post('/register/applicant', RegisterApplicantController::class);
    Route::post('/register/employer', RegisterEmployerController::class);
    Route::post('/login', LoginController::class);
    Route::post('/login-curator', CuratorLoginController::class);

    Route::middleware('auth:sanctum')->group(static function (): void {
        Route::get('/me', AuthenticatedUserController::class);
        Route::post('/logout', LogoutController::class);
    });
});


Route::get('/opportunities', OpportunityIndexController::class);
Route::get('/opportunities/{opportunity}', OpportunityShowController::class);
Route::get('/tags', TagIndexController::class);

Route::middleware('auth:sanctum')->group(static function (): void {
    Route::get('/applicant/profile', ApplicantProfileController::class);
    Route::put('/applicant/profile', UpdateApplicantProfileController::class);
});
