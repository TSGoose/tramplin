import type { Opportunity } from '@/entities/opportunity/model/types';

export interface FavoriteItem {
  id: number;
  planned_apply_at: string | null;
  opportunity: Opportunity;
}
