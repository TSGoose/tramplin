import { http } from '@/shared/api/http';
import type { Company } from '@/entities/company/model/types';
import type { Opportunity } from '@/entities/opportunity/model/types';
import type { AuditLogItem } from '@/entities/audit-log/model/types';

export interface ModerationPayload {
  action: 'approve' | 'reject' | 'needs_revision';
  comment: string | null;
}

export async function fetchCuratorCompanies(): Promise<{ data: Company[] }> {
  const { data } = await http.get<{ data: Company[] }>('/curator/companies');
  return data;
}

export async function moderateCuratorCompany(
  companyId: number,
  payload: ModerationPayload,
): Promise<{ data: Company }> {
  const { data } = await http.patch<{ data: Company }>(`/curator/companies/${companyId}/status`, payload);
  return data;
}

export async function fetchCuratorOpportunities(): Promise<{ data: Opportunity[] }> {
  const { data } = await http.get<{ data: Opportunity[] }>('/curator/opportunities');
  return data;
}

export async function moderateCuratorOpportunity(
  opportunityId: number,
  payload: ModerationPayload,
): Promise<{ data: Opportunity }> {
  const { data } = await http.patch<{ data: Opportunity }>(
    `/curator/opportunities/${opportunityId}/status`,
    payload,
  );
  return data;
}

export async function fetchCuratorAuditLogs(): Promise<{ data: AuditLogItem[] }> {
  const { data } = await http.get<{ data: AuditLogItem[] }>('/curator/audit-logs');
  return data;
}
