<template>
  <section class="page-section">
    <UiContainer>
      <div class="mb-8">
        <h1 class="page-title">Каталог возможностей</h1>
        <p class="page-subtitle mt-3 max-w-3xl">
          Стажировки, вакансии, менторские программы и карьерные события для студентов и выпускников.
        </p>
      </div>

      <UiAlert v-if="catalogStore.errorMessage" class="mb-5">
        {{ catalogStore.errorMessage }}
      </UiAlert>

      <OpportunityFilters
        v-model="catalogStore.filters"
        :tags="catalogStore.tags"
        @submit="onApplyFilters"
        @reset="onResetFilters"
      />

      <div class="mt-8 flex items-center justify-between gap-4">
        <p class="text-sm text-slate-600">
          Найдено возможностей: <span class="font-semibold text-slate-900">{{ catalogStore.opportunities.length }}</span>
        </p>

        <UiButton
          v-if="catalogStore.hasFilters"
          variant="ghost"
          @click="onResetFilters"
        >
          Очистить все
        </UiButton>
      </div>

      <div v-if="catalogStore.isLoading" class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        <UiCard
          v-for="index in 6"
          :key="index"
          className="p-6"
        >
          <div class="space-y-3">
            <div class="h-4 w-24 animate-pulse rounded bg-slate-200" />
            <div class="h-6 w-3/4 animate-pulse rounded bg-slate-200" />
            <div class="h-4 w-full animate-pulse rounded bg-slate-200" />
            <div class="h-4 w-2/3 animate-pulse rounded bg-slate-200" />
          </div>
        </UiCard>
      </div>

      <div v-else-if="catalogStore.opportunities.length === 0" class="mt-8">
        <UiCard className="p-8 text-center">
          <h2 class="text-lg font-semibold text-slate-900">Ничего не найдено</h2>
          <p class="mt-2 text-sm text-slate-600">
            Попробуй изменить параметры поиска или сбросить фильтры.
          </p>
        </UiCard>
      </div>

      <div v-else class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        <OpportunityCard
          v-for="opportunity in catalogStore.opportunities"
          :key="opportunity.id"
          :opportunity="opportunity"
        />
      </div>
    </UiContainer>
  </section>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { useAuthStore } from '@/features/auth/model/auth.store';
import { useFavoritesStore } from '@/features/favorites/model/favorites.store';
import OpportunityCard from '@/widgets/opportunities/OpportunityCard.vue';
import OpportunityFilters from '@/widgets/opportunities/OpportunityFilters.vue';
import UiAlert from '@/shared/ui/UiAlert.vue';
import UiButton from '@/shared/ui/UiButton.vue';
import UiCard from '@/shared/ui/UiCard.vue';
import UiContainer from '@/shared/ui/UiContainer.vue';
import { useOpportunityCatalogStore } from '@/features/opportunity-catalog/model/opportunityCatalog.store';

const catalogStore = useOpportunityCatalogStore();

const authStore = useAuthStore();
const favoritesStore = useFavoritesStore();

onMounted(async () => {
  await Promise.all([
    catalogStore.loadTags(),
    catalogStore.loadCatalog(),
  ]);

  if (authStore.user?.role === 'applicant') {
    await favoritesStore.loadFavorites();
  }
});

async function onApplyFilters(): Promise<void> {
  await catalogStore.loadCatalog();
}

async function onResetFilters(): Promise<void> {
  catalogStore.resetFilters();
  await catalogStore.loadCatalog();
}
</script>
