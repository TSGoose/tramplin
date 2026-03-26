<?php

declare(strict_types=1);

use App\Enums\ApplicationStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('opportunity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('applicant_profile_id')->constrained()->cascadeOnDelete();
            $table->text('cover_letter')->nullable();
            $table->string('status')->default(ApplicationStatus::New->value);
            $table->text('employer_comment')->nullable();
            $table->timestamps();

            $table->unique(['opportunity_id', 'applicant_profile_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
