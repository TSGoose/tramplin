import { computed, ref } from 'vue';
import { defineStore } from 'pinia';
import type { ApplicantProfile, UpdateApplicantProfilePayload } from '@/entities/applicant/model/types';
import {
  fetchApplicantProfile as fetchApplicantProfileRequest,
  updateApplicantProfile as updateApplicantProfileRequest,
} from '@/features/applicant-profile/api/applicantProfile.api';

export const useApplicantProfileStore = defineStore('applicant-profile', () => {
  const profile = ref<ApplicantProfile | null>(null);
  const isLoading = ref(false);
  const isSaving = ref(false);
  const errorMessage = ref<string | null>(null);
  const successMessage = ref<string | null>(null);

  const isReady = computed<boolean>(() => profile.value !== null);

  async function fetchProfile(): Promise<void> {
    isLoading.value = true;
    errorMessage.value = null;

    try {
      const response = await fetchApplicantProfileRequest();
      profile.value = response.data;
    } catch (error: unknown) {
      errorMessage.value = extractErrorMessage(error);
      throw error;
    } finally {
      isLoading.value = false;
    }
  }

  async function saveProfile(payload: UpdateApplicantProfilePayload): Promise<void> {
    isSaving.value = true;
    errorMessage.value = null;
    successMessage.value = null;

    try {
      const response = await updateApplicantProfileRequest(payload);
      profile.value = response.data;
      successMessage.value = 'Профиль успешно сохранен.';
    } catch (error: unknown) {
      errorMessage.value = extractErrorMessage(error);
      throw error;
    } finally {
      isSaving.value = false;
    }
  }

  function clearMessages(): void {
    errorMessage.value = null;
    successMessage.value = null;
  }

  return {
    profile,
    isLoading,
    isSaving,
    errorMessage,
    successMessage,
    isReady,
    fetchProfile,
    saveProfile,
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

  return 'Не удалось загрузить или сохранить профиль.';
}
