export type CompanyVerificationStatus =
  | 'draft'
  | 'pending_verification'
  | 'verified'
  | 'needs_revision'
  | 'rejected';

export interface Company {
  id: number;
  owner_user_id: number;
  name: string;
  description: string | null;
  industry: string | null;
  website_url: string | null;
  social_url: string | null;
  inn: string | null;
  city: string | null;
  address: string | null;
  verification_status: CompanyVerificationStatus | null;
  verification_comment: string | null;
  verified_at: string | null;
}

export interface UpdateCompanyPayload {
  name: string;
  description: string | null;
  industry: string | null;
  website_url: string | null;
  social_url: string | null;
  inn: string | null;
  city: string | null;
  address: string | null;
}

export interface UpsertEmployerOpportunityPayload {
  title: string;
  short_description: string | null;
  full_description: string;
  type: 'vacancy' | 'internship' | 'mentorship' | 'event';
  work_format: 'remote' | 'hybrid' | 'office' | 'online';
  employment_type: 'full_time' | 'part_time' | 'project' | 'flexible' | null;
  level: 'junior' | 'middle' | 'senior' | 'trainee' | null;
  city: string | null;
  address: string | null;
  latitude: number | null;
  longitude: number | null;
  is_remote: boolean;
  expires_at: string | null;
  event_date: string | null;
  salary_from: number | null;
  salary_to: number | null;
  contacts_text: string | null;
  external_url: string | null;
  tag_ids: number[];
}
