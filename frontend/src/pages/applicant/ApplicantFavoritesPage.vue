<template>
  <section class="page-section">
    <UiContainer>
      <div class="mb-8">
        <h1 class="page-title">Избранное</h1>
        <p class="page-subtitle mt-3">
          Возможности, которые ты сохранил для дальнейшего рассмотрения.
        </p>
      </div>

      <UiAlert v-if="favoritesStore.errorMessage" class="mb-5">
        {{ favoritesStore.errorMessage }}
      </UiAlert>

      <div v-if="favoritesStore.isLoading" class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        <UiCard v-for="index in 3" :key="index" className="p-6">
          <div class="space-y-3">
            <div class="h-4 w-24 animate-pulse rounded bg-slate-200" />
            <div class="h-6 w-3/4 animate-pulse rounded bg-slate-200" />
            <div class="h-4 w-full animate-pulse rounded bg-slate-200" />
          </div>
        </UiCard>
      </div>

      <div v-else-if="favoritesStore.items.length === 0">
        <UiCard className="p-8 text-center">
          <h2 class="text-lg font-semibold text-slate-900">Пока пусто</h2>
          <p class="mt-2 text-sm text-slate-600">
            Добавляй интересные возможности в избранное из каталога.
          </p>
        </UiCard>
      </div>

      <div v-else class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        <OpportunityCard
          v-for="item in favoritesStore.items"
          :key="item.id"
          :opportunity="item.opportunity"
        />
      </div>
    </UiContainer>
  </section>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { useFavoritesStore } from '@/features/favorites/model/favorites.store';
import OpportunityCard from '@/widgets/opportunities/OpportunityCard.vue';
import UiAlert from '@/shared/ui/UiAlert.vue';
import UiCard from '@/shared/ui/UiCard.vue';
import UiContainer from '@/shared/ui/UiContainer.vue';

const favoritesStore = useFavoritesStore();

onMounted(async () => {
  await favoritesStore.loadFavorites();
});
</script>
