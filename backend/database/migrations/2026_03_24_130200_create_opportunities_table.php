<?php

declare(strict_types=1);

use App\Enums\OpportunityStatus;
use App\Enums\OpportunityType;
use App\Enums\OpportunityWorkFormat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opportunities', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('short_description', 500)->nullable();
            $table->text('full_description');
            $table->string('type')->default(OpportunityType::Vacancy->value);
            $table->string('work_format')->default(OpportunityWorkFormat::Office->value);
            $table->string('employment_type')->nullable();
            $table->string('level')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_remote')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('event_date')->nullable();
            $table->unsignedInteger('salary_from')->nullable();
            $table->unsignedInteger('salary_to')->nullable();
            $table->text('contacts_text')->nullable();
            $table->string('external_url')->nullable();
            $table->string('status')->default(OpportunityStatus::Draft->value);
            $table->text('moderation_comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opportunities');
    }
};
