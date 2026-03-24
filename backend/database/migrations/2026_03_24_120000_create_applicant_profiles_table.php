<?php

declare(strict_types=1);

use App\Enums\ProfileModerationStatus;
use App\Enums\ProfilePrivacyLevel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applicant_profiles', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('full_name')->nullable();
            $table->string('university_name')->nullable();
            $table->unsignedSmallInteger('course')->nullable();
            $table->unsignedSmallInteger('graduation_year')->nullable();
            $table->text('about')->nullable();
            $table->string('resume_file_path')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('privacy_level')->default(ProfilePrivacyLevel::PlatformVisible->value);
            $table->json('preferred_work_formats')->nullable();
            $table->json('preferred_cities')->nullable();
            $table->unsignedInteger('profile_views_count')->default(0);
            $table->string('moderation_status')->default(ProfileModerationStatus::Unreviewed->value);
            $table->text('moderation_comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicant_profiles');
    }
};
