import { computed, ref } from 'vue';
import { defineStore } from 'pinia';
import type { FavoriteItem } from '@/entities/favorite/model/types';
import {
  addFavorite as addFavoriteRequest,
  fetchFavorites as fetchFavoritesRequest,
  removeFavorite as removeFavoriteRequest,
} from '@/features/favorites/api/favorites.api';

export const useFavoritesStore = defineStore('favorites', () => {
  const items = ref<FavoriteItem[]>([]);
  const isLoading = ref(false);
  const isMutating = ref(false);
  const errorMessage = ref<string | null>(null);

  const favoriteOpportunityIds = computed<number[]>(() =>
    items.value.map((item) => item.opportunity.id),
  );

  function isFavorite(opportunityId: number): boolean {
    return favoriteOpportunityIds.value.includes(opportunityId);
  }

  async function loadFavorites(): Promise<void> {
    isLoading.value = true;
    errorMessage.value = null;

    try {
      const response = await fetchFavoritesRequest();
      items.value = response.data;
    } catch (error: unknown) {
      errorMessage.value = extractErrorMessage(error);
      throw error;
    } finally {
      isLoading.value = false;
    }
  }

  async function add(opportunityId: number): Promise<void> {
    isMutating.value = true;
    errorMessage.value = null;

    try {
      const response = await addFavoriteRequest(opportunityId);

      if (!isFavorite(opportunityId)) {
        items.value = [response.data, ...items.value];
      }
    } catch (error: unknown) {
      errorMessage.value = extractErrorMessage(error);
      throw error;
    } finally {
      isMutating.value = false;
    }
  }

  async function remove(opportunityId: number): Promise<void> {
    isMutating.value = true;
    errorMessage.value = null;

    try {
      await removeFavoriteRequest(opportunityId);
      items.value = items.value.filter((item) => item.opportunity.id !== opportunityId);
    } catch (error: unknown) {
      errorMessage.value = extractErrorMessage(error);
      throw error;
    } finally {
      isMutating.value = false;
    }
  }

  async function toggle(opportunityId: number): Promise<void> {
    if (isFavorite(opportunityId)) {
      await remove(opportunityId);
      return;
    }

    await add(opportunityId);
  }

  return {
    items,
    isLoading,
    isMutating,
    errorMessage,
    favoriteOpportunityIds,
    isFavorite,
    loadFavorites,
    add,
    remove,
    toggle,
  };
});

function extractErrorMessage(error: unknown): string {
  if (
    typeof error === 'object' &&
    error !== null &&
    'response' in error &&
    typeof error.response === 'object' &&
    error.response !== null &&
    'data' in error.response
  ) {
    const responseData = error.response.data as { message?: string };

    if (responseData.message) {
      return responseData.message;
    }
  }

  return 'Не удалось выполнить действие с избранным.';
}
