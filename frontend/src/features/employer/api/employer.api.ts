import { http } from '@/shared/api/http';
import type { Company, UpdateCompanyPayload, UpsertEmployerOpportunityPayload } from '@/entities/company/model/types';
import type { Opportunity, OpportunityTag } from '@/entities/opportunity/model/types';

export async function fetchEmployerCompany(): Promise<{ data: Company }> {
  const { data } = await http.get<{ data: Company }>('/employer/company');
  return data;
}

export async function updateEmployerCompany(payload: UpdateCompanyPayload): Promise<{ data: Company }> {
  const { data } = await http.put<{ data: Company }>('/employer/company', payload);
  return data;
}

export async function submitEmployerCompanyVerification(): Promise<{ data: Company }> {
  const { data } = await http.post<{ data: Company }>('/employer/company/verification-submit');
  return data;
}

export async function fetchEmployerOpportunities(): Promise<{ data: Opportunity[] }> {
  const { data } = await http.get<{ data: Opportunity[] }>('/employer/opportunities');
  return data;
}

export async function createEmployerOpportunity(
  payload: UpsertEmployerOpportunityPayload,
): Promise<{ data: Opportunity }> {
  const { data } = await http.post<{ data: Opportunity }>('/employer/opportunities', payload);
  return data;
}

export async function updateEmployerOpportunity(
  id: number,
  payload: UpsertEmployerOpportunityPayload,
): Promise<{ data: Opportunity }> {
  const { data } = await http.put<{ data: Opportunity }>(`/employer/opportunities/${id}`, payload);
  return data;
}

export async function submitEmployerOpportunity(id: number): Promise<{ data: Opportunity }> {
  const { data } = await http.post<{ data: Opportunity }>(`/employer/opportunities/${id}/submit`);
  return data;
}

export async function fetchTagsForEmployer(): Promise<{ data: OpportunityTag[] }> {
  const { data } = await http.get<{ data: OpportunityTag[] }>('/tags');
  return data;
}
