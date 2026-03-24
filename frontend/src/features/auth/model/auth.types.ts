
import type { CurrentUser } from '@/entities/user/model/types';

export interface AuthResponse {
  token: string;
  data: CurrentUser;
}

export interface RegisterPayload {
  display_name: string;
  email: string;
  password: string;
  password_confirmation: string;
}

export interface LoginPayload {
  email: string;
  password: string;
}
