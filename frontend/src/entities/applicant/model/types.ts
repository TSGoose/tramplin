
export type ProfilePrivacyLevel = 'private' | 'contacts_only' | 'platform_visible';

export interface ApplicantProfile {
  id: number;
  user_id: number;
  full_name: string | null;
  university_name: string | null;
  course: number | null;
  graduation_year: number | null;
  about: string | null;
  resume_file_path: string | null;
  portfolio_url: string | null;
  github_url: string | null;
  privacy_level: ProfilePrivacyLevel;
  preferred_work_formats: string[];
  preferred_cities: string[];
  profile_views_count: number;
  moderation_status: 'unreviewed' | 'approved' | 'needs_revision' | 'rejected';
  moderation_comment: string | null;
}

export interface UpdateApplicantProfilePayload {
  full_name: string | null;
  university_name: string | null;
  course: number | null;
  graduation_year: number | null;
  about: string | null;
  portfolio_url: string | null;
  github_url: string | null;
  privacy_level: ProfilePrivacyLevel;
  preferred_work_formats: string[];
  preferred_cities: string[];
}
