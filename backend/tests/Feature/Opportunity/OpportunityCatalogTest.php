<?php

declare(strict_types=1);

namespace Tests\Feature\Opportunity;

use App\Enums\OpportunityStatus;
use App\Enums\OpportunityType;
use App\Models\Company;
use App\Models\Opportunity;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class OpportunityCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_catalog_returns_only_published_opportunities(): void
    {
        Opportunity::factory()->create([
            'status' => OpportunityStatus::Published,
            'title' => 'Published Opportunity',
        ]);

        Opportunity::factory()->create([
            'status' => OpportunityStatus::Draft,
            'title' => 'Draft Opportunity',
        ]);

        $response = $this->getJson('/api/opportunities');

        $response
            ->assertOk()
            ->assertJsonFragment(['title' => 'Published Opportunity'])
            ->assertJsonMissing(['title' => 'Draft Opportunity']);
    }

    public function test_catalog_can_filter_by_type(): void
    {
        Opportunity::factory()->create([
            'status' => OpportunityStatus::Published,
            'type' => OpportunityType::Vacancy,
            'title' => 'Vacancy Opportunity',
        ]);

        Opportunity::factory()->create([
            'status' => OpportunityStatus::Published,
            'type' => OpportunityType::Internship,
            'title' => 'Internship Opportunity',
        ]);

        $response = $this->getJson('/api/opportunities?type=vacancy');

        $response
            ->assertOk()
            ->assertJsonFragment(['title' => 'Vacancy Opportunity'])
            ->assertJsonMissing(['title' => 'Internship Opportunity']);
    }

    public function test_catalog_can_filter_by_tag(): void
    {
        $tag = Tag::factory()->create([
            'name' => 'Vue',
            'slug' => 'vue',
        ]);

        $opportunity = Opportunity::factory()->create([
            'status' => OpportunityStatus::Published,
            'title' => 'Vue Opportunity',
        ]);

        $opportunity->tags()->attach($tag->id);

        $response = $this->getJson('/api/opportunities?tag=vue');

        $response
            ->assertOk()
            ->assertJsonFragment(['title' => 'Vue Opportunity']);
    }

    public function test_opportunity_show_returns_published_opportunity(): void
    {
        $company = Company::factory()->create();

        $opportunity = Opportunity::factory()->for($company)->create([
            'status' => OpportunityStatus::Published,
        ]);

        $response = $this->getJson("/api/opportunities/{$opportunity->id}");

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $opportunity->id);
    }

    public function test_unpublished_opportunity_show_returns_404(): void
    {
        $opportunity = Opportunity::factory()->create([
            'status' => OpportunityStatus::Draft,
        ]);

        $response = $this->getJson("/api/opportunities/{$opportunity->id}");

        $response->assertNotFound();
    }

    public function test_tags_endpoint_returns_tags(): void
    {
        Tag::factory()->create([
            'name' => 'Vue',
            'slug' => 'vue',
        ]);

        $response = $this->getJson('/api/tags');

        $response
            ->assertOk()
            ->assertJsonFragment(['slug' => 'vue']);
    }
}
