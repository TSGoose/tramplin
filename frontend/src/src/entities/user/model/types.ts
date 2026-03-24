export type UserRole = 'applicant' | 'employer' | 'curator' | 'admin';

export interface CurrentUser {
  id: number;
  display_name: string;
  email: string;
  role: UserRole;
}
