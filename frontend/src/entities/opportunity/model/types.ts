export type OpportunityType = 'vacancy' | 'internship' | 'mentorship' | 'event';
export type OpportunityWorkFormat = 'remote' | 'hybrid' | 'office' | 'online';
export type OpportunityEmploymentType = 'full_time' | 'part_time' | 'project' | 'flexible';
export type OpportunityLevel = 'junior' | 'middle' | 'senior' | 'trainee';

export interface OpportunityTag {
  id: number;
  name: string;
  slug: string;
  group: string | null;
}

export interface OpportunityCompany {
  id: number;
  name: string;
  city: string | null;
}

export interface Opportunity {
  id: number;
  title: string;
  short_description: string | null;
  full_description: string;
  type: OpportunityType | null;
  work_format: OpportunityWorkFormat | null;
  employment_type: OpportunityEmploymentType | null;
  level: OpportunityLevel | null;
  city: string | null;
  address: string | null;
  latitude: string | number | null;
  longitude: string | number | null;
  is_remote: boolean;
  published_at: string | null;
  expires_at: string | null;
  event_date: string | null;
  salary_from: number | null;
  salary_to: number | null;
  contacts_text: string | null;
  external_url: string | null;
  company: OpportunityCompany;
  tags: OpportunityTag[];
  status:
  | 'draft'
  | 'pending_moderation'
  | 'published'
  | 'needs_revision'
  | 'rejected'
  | 'archived'
  | 'expired'
  | null;
}

export interface OpportunityListResponse {
  data: Opportunity[];
  links?: Record<string, unknown>;
  meta?: Record<string, unknown>;
}

export interface OpportunityFilters {
  search: string;
  type: string;
  work_format: string;
  city: string;
  tag: string;
}
