import { http } from '@/shared/api/http';
import type { ApplicationItem, CreateApplicationPayload } from '@/entities/application/model/types';

export async function fetchApplications(): Promise<{ data: ApplicationItem[] }> {
  const { data } = await http.get<{ data: ApplicationItem[] }>('/applicant/applications');
  return data;
}

export async function createApplication(
  payload: CreateApplicationPayload,
): Promise<{ data: ApplicationItem }> {
  const { data } = await http.post<{ data: ApplicationItem }>('/applicant/applications', payload);
  return data;
}
