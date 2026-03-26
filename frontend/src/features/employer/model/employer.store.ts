import { ref } from 'vue';
import { defineStore } from 'pinia';
import type { Company, UpdateCompanyPayload, UpsertEmployerOpportunityPayload } from '@/entities/company/model/types';
import type { Opportunity, OpportunityTag } from '@/entities/opportunity/model/types';
import {
  createEmployerOpportunity,
  fetchEmployerCompany,
  fetchEmployerOpportunities,
  fetchTagsForEmployer,
  submitEmployerCompanyVerification,
  submitEmployerOpportunity,
  updateEmployerCompany,
} from '@/features/employer/api/employer.api';

export const useEmployerStore = defineStore('employer', () => {
  const company = ref<Company | null>(null);
  const opportunities = ref<Opportunity[]>([]);
  const tags = ref<OpportunityTag[]>([]);
  const isCompanyLoading = ref(false);
  const isOpportunitiesLoading = ref(false);
  const isSubmitting = ref(false);
  const errorMessage = ref<string | null>(null);
  const successMessage = ref<string | null>(null);

  async function loadCompany(): Promise<void> {
    isCompanyLoading.value = true;
    errorMessage.value = null;

    try {
      const response = await fetchEmployerCompany();
      company.value = response.data;
    } catch (error: unknown) {
      errorMessage.value = extractErrorMessage(error);
      throw error;
    } finally {
      isCompanyLoading.value = false;
    }
  }

  async function saveCompany(payload: UpdateCompanyPayload): Promise<void> {
    isSubmitting.value = true;
    errorMessage.value = null;
    successMessage.value = null;

    try {
      const response = await updateEmployerCompany(payload);
      company.value = response.data;
      successMessage.value = 'Профиль компании сохранен.';
    } catch (error: unknown) {
      errorMessage.value = extractErrorMessage(error);
      throw error;
    } finally {
      isSubmitting.value = false;
    }
  }

  async function sendCompanyToVerification(): Promise<void> {
    isSubmitting.value = true;
    errorMessage.value = null;
    successMessage.value = null;

    try {
      const response = await submitEmployerCompanyVerification();
      company.value = response.data;
      successMessage.value = 'Компания отправлена на верификацию.';
    } catch (error: unknown) {
      errorMessage.value = extractErrorMessage(error);
      throw error;
    } finally {
      isSubmitting.value = false;
    }
  }

  async function loadOpportunities(): Promise<void> {
    isOpportunitiesLoading.value = true;
    errorMessage.value = null;

    try {
      const response = await fetchEmployerOpportunities();
      opportunities.value = response.data;
    } catch (error: unknown) {
      errorMessage.value = extractErrorMessage(error);
      throw error;
    } finally {
      isOpportunitiesLoading.value = false;
    }
  }

  async function loadTags(): Promise<void> {
    const response = await fetchTagsForEmployer();
    tags.value = response.data;
  }

  async function createOpportunity(payload: UpsertEmployerOpportunityPayload): Promise<Opportunity> {
    isSubmitting.value = true;
    errorMessage.value = null;
    successMessage.value = null;

    try {
      const response = await createEmployerOpportunity(payload);
      opportunities.value = [response.data, ...opportunities.value];
      successMessage.value = 'Возможность создана как черновик.';
      return response.data;
    } catch (error: unknown) {
      errorMessage.value = extractErrorMessage(error);
      throw error;
    } finally {
      isSubmitting.value = false;
    }
  }

  async function sendOpportunityToModeration(id: number): Promise<void> {
    isSubmitting.value = true;
    errorMessage.value = null;
    successMessage.value = null;

    try {
      await submitEmployerOpportunity(id);
      await loadOpportunities();
      successMessage.value = 'Возможность отправлена на модерацию.';
    } catch (error: unknown) {
      errorMessage.value = extractErrorMessage(error);
      throw error;
    } finally {
      isSubmitting.value = false;
    }
}

  function clearMessages(): void {
    errorMessage.value = null;
    successMessage.value = null;
  }

  return {
    company,
    opportunities,
    tags,
    isCompanyLoading,
    isOpportunitiesLoading,
    isSubmitting,
    errorMessage,
    successMessage,
    loadCompany,
    saveCompany,
    sendCompanyToVerification,
    loadOpportunities,
    loadTags,
    createOpportunity,
    sendOpportunityToModeration,
    clearMessages,
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
    const responseData = error.response.data as {
      message?: string;
      errors?: Record<string, string[]>;
    };

    if (responseData.message) {
      return responseData.message;
    }

    const firstValidationError = responseData.errors
      ? Object.values(responseData.errors)[0]?.[0]
      : null;

    if (firstValidationError) {
      return firstValidationError;
    }
  }

  return 'Не удалось выполнить действие работодателя.';
}
