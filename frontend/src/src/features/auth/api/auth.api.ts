import { http } from '@/shared/api/http';
import type { AuthResponse, LoginPayload, RegisterPayload } from '@/features/auth/model/auth.types';
import type { CurrentUser } from '@/entities/user/model/types';

export async function registerApplicant(payload: RegisterPayload): Promise<AuthResponse> {
  const { data } = await http.post<AuthResponse>('/auth/register/applicant', payload);
  return data;
}

export async function registerEmployer(payload: RegisterPayload): Promise<AuthResponse> {
  const { data } = await http.post<AuthResponse>('/auth/register/employer', payload);
  return data;
}

export async function login(payload: LoginPayload): Promise<AuthResponse> {
  const { data } = await http.post<AuthResponse>('/auth/login', payload);
  return data;
}

export async function loginCurator(payload: LoginPayload): Promise<AuthResponse> {
  const { data } = await http.post<AuthResponse>('/auth/login-curator', payload);
  return data;
}

export async function fetchMe(): Promise<{ data: CurrentUser }> {
  const { data } = await http.get<{ data: CurrentUser }>('/auth/me');
  return data;
}

export async function logout(): Promise<void> {
  await http.post('/auth/logout');
}
