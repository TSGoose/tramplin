import type { Opportunity } from '@/entities/opportunity/model/types';

export type ApplicationStatus =
  | 'new'
  | 'reviewing'
  | 'interview'
  | 'reserve'
  | 'accepted'
  | 'rejected';

export interface ApplicationItem {
  id: number;
  cover_letter: string | null;
  status: ApplicationStatus;
  employer_comment: string | null;
  created_at: string | null;
  opportunity: Opportunity;
}

export interface CreateApplicationPayload {
  opportunity_id: number;
  cover_letter: string | null;
}
