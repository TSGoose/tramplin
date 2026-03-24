import { z } from 'zod';

export const loginSchema = z.object({
  email: z
    .string()
    .min(1, 'Укажите email')
    .email('Введите корректный email'),
  password: z
    .string()
    .min(1, 'Укажите пароль')
    .min(8, 'Пароль должен содержать минимум 8 символов'),
});

export const registerSchema = z.object({
  display_name: z
    .string()
    .min(1, 'Укажите имя')
    .max(255, 'Слишком длинное имя'),
  email: z
    .string()
    .min(1, 'Укажите email')
    .email('Введите корректный email'),
  password: z
    .string()
    .min(8, 'Пароль должен содержать минимум 8 символов'),
  password_confirmation: z
    .string()
    .min(8, 'Подтвердите пароль'),
}).refine((data) => data.password === data.password_confirmation, {
  message: 'Пароли не совпадают',
  path: ['password_confirmation'],
});

export type LoginFormValues = z.infer<typeof loginSchema>;
export type RegisterFormValues = z.infer<typeof registerSchema>;
