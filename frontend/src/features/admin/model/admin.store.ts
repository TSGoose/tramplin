import { ref } from 'vue';
import { defineStore } from 'pinia';
import type { CurrentUser } from '@/entities/user/model/types';
import type { CreateCuratorPayload } from '@/features/admin/api/admin.api';
import { createCurator, fetchAdminUsers } from '@/features/admin/api/admin.api';

export const useAdminStore = defineStore('admin', () => {
  const users = ref<CurrentUser[]>([]);
  const selectedRole = ref('');
  const isLoading = ref(false);
  const isSubmitting = ref(false);
  const errorMessage = ref<string | null>(null);
  const successMessage = ref<string | null>(null);

  async function loadUsers(): Promise<void> {
    isLoading.value = true;
    errorMessage.value = null;

    try {
      const response = await fetchAdminUsers(selectedRole.value);
      users.value = response.data;
    } catch (error: unknown) {
      errorMessage.value = extractErrorMessage(error);
      throw error;
    } finally {
      isLoading.value = false;
    }
  }

  async function createCuratorUser(payload: CreateCuratorPayload): Promise<void> {
    isSubmitting.value = true;
    errorMessage.value = null;
    successMessage.value = null;

    try {
      await createCurator(payload);
      await loadUsers();
      successMessage.value = 'Куратор успешно создан.';
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
    users,
    selectedRole,
    isLoading,
    isSubmitting,
    errorMessage,
    successMessage,
    loadUsers,
    createCuratorUser,
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

  return 'Не удалось выполнить действие администратора.';
}
