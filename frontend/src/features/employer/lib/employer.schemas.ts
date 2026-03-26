import { z } from 'zod';

export const createEmployerOpportunitySchema = z.object({
  title: z.string().min(1, 'Укажи название'),
  full_description: z.string().min(1, 'Добавь полное описание'),
  type: z.enum(['vacancy', 'internship', 'mentorship', 'event']),
  work_format: z.enum(['remote', 'hybrid', 'office', 'online']),
  is_remote: z.boolean(),
});
