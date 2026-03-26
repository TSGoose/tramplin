import { computed, reactive, ref } from 'vue';
import { defineStore } from 'pinia';
import type {
  Opportunity,
  OpportunityFilters,
  OpportunityTag,
} from '@/entities/opportunity/model/types';
import {
  fetchOpportunityById,
  fetchOpportunities,
  fetchTags,
} from '@/features/opportunity-catalog/api/opportunityCatalog.api';

const defaultFilters = (): OpportunityFilters => ({
  search: '',
  type: '',
  work_format: '',
  city: '',
  tag: '',
});

export const useOpportunityCatalogStore = defineStore('opportunity-catalog', () => {
  const opportunities = ref<Opportunity[]>([]);
  const selectedOpportunity = ref<Opportunity | null>(null);
  const tags = ref<OpportunityTag[]>([]);
  const filters = reactive<OpportunityFilters>(defaultFilters());
  const isLoading = ref(false);
  const isDetailsLoading = ref(false);
  const isTagsLoading = ref(false);
  const errorMessage = ref<string | null>(null);

  const hasFilters = computed<boolean>(() =>
    Object.values(filters).some((value) => value.trim().length > 0),
  );

  async function loadCatalog(): Promise<void> {
    isLoading.value = true;
    errorMessage.value = null;

    try {
      const response = await fetchOpportunities(filters);
      opportunities.value = response.data;
    } catch (error: unknown) {
      errorMessage.value = extractErrorMessage(error);
      throw error;
    } finally {
      isLoading.value = false;
    }
  }

  async function loadTags(): Promise<void> {
    isTagsLoading.value = true;

    try {
      const response = await fetchTags();
      tags.value = response.data;
    } finally {
      isTagsLoading.value = false;
    }
  }

  async function loadOpportunity(id: number | string): Promise<void> {
    isDetailsLoading.value = true;
    errorMessage.value = null;

    try {
      const response = await fetchOpportunityById(id);
      selectedOpportunity.value = response.data;
    } catch (error: unknown) {
      errorMessage.value = extractErrorMessage(error);
      throw error;
    } finally {
      isDetailsLoading.value = false;
    }
  }

  function resetFilters(): void {
    Object.assign(filters, defaultFilters());
  }

  return {
    opportunities,
    selectedOpportunity,
    tags,
    filters,
    isLoading,
    isDetailsLoading,
    isTagsLoading,
    errorMessage,
    hasFilters,
    loadCatalog,
    loadTags,
    loadOpportunity,
    resetFilters,
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

  return 'Не удалось загрузить данные каталога.';
}
