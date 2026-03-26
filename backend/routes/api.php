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
use App\Http\Controllers\Api\Applicant\ApplicationIndexController;
use App\Http\Controllers\Api\Applicant\DeleteFavoriteController;
use App\Http\Controllers\Api\Applicant\FavoriteIndexController;
use App\Http\Controllers\Api\Applicant\StoreApplicationController;
use App\Http\Controllers\Api\Applicant\StoreFavoriteController;
use App\Http\Controllers\Api\Employer\EmployerCompanyShowController;
use App\Http\Controllers\Api\Employer\EmployerCompanySubmitVerificationController;
use App\Http\Controllers\Api\Employer\EmployerCompanyUpdateController;
use App\Http\Controllers\Api\Employer\EmployerOpportunityIndexController;
use App\Http\Controllers\Api\Employer\EmployerOpportunityShowController;
use App\Http\Controllers\Api\Employer\EmployerOpportunityStoreController;
use App\Http\Controllers\Api\Employer\EmployerOpportunitySubmitController;
use App\Http\Controllers\Api\Employer\EmployerOpportunityUpdateController;
use App\Http\Controllers\Api\Curator\CuratorAuditLogIndexController;
use App\Http\Controllers\Api\Curator\CuratorCompanyIndexController;
use App\Http\Controllers\Api\Curator\CuratorCompanyModerationController;
use App\Http\Controllers\Api\Curator\CuratorOpportunityIndexController;
use App\Http\Controllers\Api\Curator\CuratorOpportunityModerationController;
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
    Route::get('/applicant/favorites', FavoriteIndexController::class);
    Route::post('/applicant/favorites/{opportunity}', StoreFavoriteController::class);
    Route::delete('/applicant/favorites/{opportunity}', DeleteFavoriteController::class);

    Route::get('/applicant/applications', ApplicationIndexController::class);
    Route::post('/applicant/applications', StoreApplicationController::class);

    Route::get('/employer/company', EmployerCompanyShowController::class);
    Route::put('/employer/company', EmployerCompanyUpdateController::class);
    Route::post('/employer/company/verification-submit', EmployerCompanySubmitVerificationController::class);

    Route::get('/employer/opportunities', EmployerOpportunityIndexController::class);
    Route::post('/employer/opportunities', EmployerOpportunityStoreController::class);
    Route::get('/employer/opportunities/{opportunity}', EmployerOpportunityShowController::class);
    Route::put('/employer/opportunities/{opportunity}', EmployerOpportunityUpdateController::class);
    Route::post('/employer/opportunities/{opportunity}/submit', EmployerOpportunitySubmitController::class);


    Route::get('/curator/companies', CuratorCompanyIndexController::class);
    Route::patch('/curator/companies/{company}/status', CuratorCompanyModerationController::class);

    Route::get('/curator/opportunities', CuratorOpportunityIndexController::class);
    Route::patch('/curator/opportunities/{opportunity}/status', CuratorOpportunityModerationController::class);

    Route::get('/curator/audit-logs', CuratorAuditLogIndexController::class);
});
