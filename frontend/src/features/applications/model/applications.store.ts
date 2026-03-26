import { ref } from 'vue';
import { defineStore } from 'pinia';
import type { ApplicationItem, CreateApplicationPayload } from '@/entities/application/model/types';
import {
  createApplication as createApplicationRequest,
  fetchApplications as fetchApplicationsRequest,
} from '@/features/applications/api/applications.api';

export const useApplicationsStore = defineStore('applications', () => {
  const items = ref<ApplicationItem[]>([]);
  const isLoading = ref(false);
  const isSubmitting = ref(false);
  const errorMessage = ref<string | null>(null);
  const successMessage = ref<string | null>(null);

  async function loadApplications(): Promise<void> {
    isLoading.value = true;
    errorMessage.value = null;

    try {
      const response = await fetchApplicationsRequest();
      items.value = response.data;
    } catch (error: unknown) {
      errorMessage.value = extractErrorMessage(error);
      throw error;
    } finally {
      isLoading.value = false;
    }
  }

  async function apply(payload: CreateApplicationPayload): Promise<void> {
    isSubmitting.value = true;
    errorMessage.value = null;
    successMessage.value = null;

    try {
      const response = await createApplicationRequest(payload);

      const exists = items.value.some((item) => item.id === response.data.id);

      if (!exists) {
        items.value = [response.data, ...items.value];
      }

      successMessage.value = 'Отклик успешно отправлен.';
    } catch (error: unknown) {
      errorMessage.value = extractErrorMessage(error);
      throw error;
    } finally {
      isSubmitting.value = false;
    }
  }

  function hasApplied(opportunityId: number): boolean {
    return items.value.some((item) => item.opportunity.id === opportunityId);
  }

  function clearMessages(): void {
    errorMessage.value = null;
    successMessage.value = null;
  }

  return {
    items,
    isLoading,
    isSubmitting,
    errorMessage,
    successMessage,
    loadApplications,
    apply,
    hasApplied,
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

  return 'Не удалось отправить отклик.';
}
