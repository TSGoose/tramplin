import { http } from '@/shared/api/http';
import type {
  Opportunity,
  OpportunityFilters,
  OpportunityListResponse,
  OpportunityTag,
} from '@/entities/opportunity/model/types';

export async function fetchOpportunities(
  filters: Partial<OpportunityFilters>,
): Promise<OpportunityListResponse> {
  const { data } = await http.get<OpportunityListResponse>('/opportunities', {
    params: normalizeFilters(filters),
  });

  return data;
}

export async function fetchOpportunityById(id: number | string): Promise<{ data: Opportunity }> {
  const { data } = await http.get<{ data: Opportunity }>(`/opportunities/${id}`);
  return data;
}

export async function fetchTags(): Promise<{ data: OpportunityTag[] }> {
  const { data } = await http.get<{ data: OpportunityTag[] }>('/tags');
  return data;
}

function normalizeFilters(filters: Partial<OpportunityFilters>): Record<string, string> {
  return Object.entries(filters).reduce<Record<string, string>>((accumulator, [key, value]) => {
    if (typeof value === 'string' && value.trim().length > 0) {
      accumulator[key] = value.trim();
    }

    return accumulator;
  }, {});
}
