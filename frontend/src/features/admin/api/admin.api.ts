import { http } from '@/shared/api/http';
import type { CurrentUser } from '@/entities/user/model/types';

export interface CreateCuratorPayload {
  display_name: string;
  email: string;
  password: string;
  password_confirmation: string;
}

export async function fetchAdminUsers(role = ''): Promise<{ data: CurrentUser[] }> {
  const { data } = await http.get<{ data: CurrentUser[] }>('/admin/users', {
    params: role ? { role } : {},
  });

  return data;
}

export async function createCurator(payload: CreateCuratorPayload): Promise<{ data: CurrentUser }> {
  const { data } = await http.post<{ data: CurrentUser }>('/admin/users/curator', payload);
  return data;
}
