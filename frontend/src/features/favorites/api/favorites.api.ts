import { http } from '@/shared/api/http';
import type { FavoriteItem } from '@/entities/favorite/model/types';

export async function fetchFavorites(): Promise<{ data: FavoriteItem[] }> {
  const { data } = await http.get<{ data: FavoriteItem[] }>('/applicant/favorites');
  return data;
}

export async function addFavorite(opportunityId: number): Promise<{ data: FavoriteItem }> {
  const { data } = await http.post<{ data: FavoriteItem }>(`/applicant/favorites/${opportunityId}`);
  return data;
}

export async function removeFavorite(opportunityId: number): Promise<void> {
  await http.delete(`/applicant/favorites/${opportunityId}`);
}
