import { http } from '@/shared/api/http';
import type { ApplicantProfile, UpdateApplicantProfilePayload } from '@/entities/applicant/model/types';

export async function fetchApplicantProfile(): Promise<{ data: ApplicantProfile }> {
  const { data } = await http.get<{ data: ApplicantProfile }>('/applicant/profile');
  return data;
}

export async function updateApplicantProfile(
  payload: UpdateApplicantProfilePayload,
): Promise<{ data: ApplicantProfile }> {
  const { data } = await http.put<{ data: ApplicantProfile }>('/applicant/profile', payload);
  return data;
}
