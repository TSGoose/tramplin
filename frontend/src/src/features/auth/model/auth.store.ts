import { computed, ref } from 'vue';
import { defineStore } from 'pinia';
import { http } from '@/shared/api/http';
import type { CurrentUser } from '@/entities/user/model/types';
import type { LoginPayload, RegisterPayload } from '@/features/auth/model/auth.types';
import {
  fetchMe as fetchMeRequest,
  login as loginRequest,
  loginCurator as loginCuratorRequest,
  logout as logoutRequest,
  registerApplicant as registerApplicantRequest,
  registerEmployer as registerEmployerRequest,
} from '@/features/auth/api/auth.api';

const AUTH_TOKEN_KEY = 'tramplin_auth_token';

export const useAuthStore = defineStore('auth', () => {
  const user = ref<CurrentUser | null>(null);
  const token = ref<string | null>(localStorage.getItem(AUTH_TOKEN_KEY));
  const isLoading = ref(false);
  const errorMessage = ref<string | null>(null);

  const isAuthenticated = computed<boolean>(() => Boolean(token.value && user.value));

  function applyToken(authToken: string | null): void {
    token.value = authToken;

    if (authToken) {
      localStorage.setItem(AUTH_TOKEN_KEY, authToken);
      http.defaults.headers.common.Authorization = `Bearer ${authToken}`;
      return;
    }

    localStorage.removeItem(AUTH_TOKEN_KEY);
    delete http.defaults.headers.common.Authorization;
  }

  async function bootstrap(): Promise<void> {
    if (!token.value) {
      return;
    }

    applyToken(token.value);

    try {
      await fetchMe();
    } catch {
      clearAuth();
    }
  }

  async function registerApplicant(payload: RegisterPayload): Promise<void> {
    isLoading.value = true;
    errorMessage.value = null;

    try {
      const response = await registerApplicantRequest(payload);
      applyToken(response.token);
      user.value = response.data;
    } catch (error: unknown) {
      errorMessage.value = extractErrorMessage(error);
      throw error;
    } finally {
      isLoading.value = false;
    }
  }

  async function registerEmployer(payload: RegisterPayload): Promise<void> {
    isLoading.value = true;
    errorMessage.value = null;

    try {
      const response = await registerEmployerRequest(payload);
      applyToken(response.token);
      user.value = response.data;
    } catch (error: unknown) {
      errorMessage.value = extractErrorMessage(error);
      throw error;
    } finally {
      isLoading.value = false;
    }
  }

  async function login(payload: LoginPayload): Promise<void> {
    isLoading.value = true;
    errorMessage.value = null;

    try {
      const response = await loginRequest(payload);
      applyToken(response.token);
      user.value = response.data;
    } catch (error: unknown) {
      errorMessage.value = extractErrorMessage(error);
      throw error;
    } finally {
      isLoading.value = false;
    }
  }

  async function loginCurator(payload: LoginPayload): Promise<void> {
    isLoading.value = true;
    errorMessage.value = null;

    try {
      const response = await loginCuratorRequest(payload);
      applyToken(response.token);
      user.value = response.data;
    } catch (error: unknown) {
      errorMessage.value = extractErrorMessage(error);
      throw error;
    } finally {
      isLoading.value = false;
    }
  }

  async function fetchMe(): Promise<void> {
    const response = await fetchMeRequest();
    user.value = response.data;
  }

  async function logout(): Promise<void> {
    try {
      await logoutRequest();
    } finally {
      clearAuth();
    }
  }

  function clearAuth(): void {
    user.value = null;
    errorMessage.value = null;
    applyToken(null);
  }

  return {
    user,
    token,
    isLoading,
    errorMessage,
    isAuthenticated,
    bootstrap,
    registerApplicant,
    registerEmployer,
    login,
    loginCurator,
    fetchMe,
    logout,
    clearAuth,
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

  return 'Не удалось выполнить запрос. Попробуйте еще раз.';
}
