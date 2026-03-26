import { ref } from 'vue';
import { defineStore } from 'pinia';
import type { Company } from '@/entities/company/model/types';
import type { Opportunity } from '@/entities/opportunity/model/types';
import type { AuditLogItem } from '@/entities/audit-log/model/types';
import type { ModerationPayload } from '@/features/curator/api/curator.api';
import {
  fetchCuratorAuditLogs,
  fetchCuratorCompanies,
  fetchCuratorOpportunities,
  moderateCuratorCompany,
  moderateCuratorOpportunity,
} from '@/features/curator/api/curator.api';

export const useCuratorStore = defineStore('curator', () => {
  const companies = ref<Company[]>([]);
  const opportunities = ref<Opportunity[]>([]);
  const auditLogs = ref<AuditLogItem[]>([]);
  const isCompaniesLoading = ref(false);
  const isOpportunitiesLoading = ref(false);
  const isAuditLoading = ref(false);
  const isSubmitting = ref(false);
  const errorMessage = ref<string | null>(null);
  const successMessage = ref<string | null>(null);

  async function loadCompanies(): Promise<void> {
    isCompaniesLoading.value = true;
    errorMessage.value = null;

    try {
      const response = await fetchCuratorCompanies();
      companies.value = response.data;
    } catch (error: unknown) {
      errorMessage.value = extractErrorMessage(error);
      throw error;
    } finally {
      isCompaniesLoading.value = false;
    }
  }

  async function loadOpportunities(): Promise<void> {
    isOpportunitiesLoading.value = true;
    errorMessage.value = null;

    try {
      const response = await fetchCuratorOpportunities();
      opportunities.value = response.data;
    } catch (error: unknown) {
      errorMessage.value = extractErrorMessage(error);
      throw error;
    } finally {
      isOpportunitiesLoading.value = false;
    }
  }

  async function loadAuditLogs(): Promise<void> {
    isAuditLoading.value = true;
    errorMessage.value = null;

    try {
      const response = await fetchCuratorAuditLogs();
      auditLogs.value = response.data;
    } catch (error: unknown) {
      errorMessage.value = extractErrorMessage(error);
      throw error;
    } finally {
      isAuditLoading.value = false;
    }
  }

  async function moderateCompany(companyId: number, payload: ModerationPayload): Promise<void> {
    isSubmitting.value = true;
    errorMessage.value = null;
    successMessage.value = null;

    try {
      await moderateCuratorCompany(companyId, payload);
      await Promise.all([loadCompanies(), loadAuditLogs()]);
      successMessage.value = 'Статус компании обновлен.';
    } catch (error: unknown) {
      errorMessage.value = extractErrorMessage(error);
      throw error;
    } finally {
      isSubmitting.value = false;
    }
  }

  async function moderateOpportunity(opportunityId: number, payload: ModerationPayload): Promise<void> {
    isSubmitting.value = true;
    errorMessage.value = null;
    successMessage.value = null;

    try {
      await moderateCuratorOpportunity(opportunityId, payload);
      await Promise.all([loadOpportunities(), loadAuditLogs()]);
      successMessage.value = 'Статус возможности обновлен.';
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
    companies,
    opportunities,
    auditLogs,
    isCompaniesLoading,
    isOpportunitiesLoading,
    isAuditLoading,
    isSubmitting,
    errorMessage,
    successMessage,
    loadCompanies,
    loadOpportunities,
    loadAuditLogs,
    moderateCompany,
    moderateOpportunity,
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

  return 'Не удалось выполнить действие куратора.';
}
