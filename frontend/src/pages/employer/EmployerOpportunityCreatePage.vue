<template>
  <section class="page-section">
    <UiContainer className="max-w-5xl">
      <div class="mb-8">
        <h1 class="page-title">Новая возможность</h1>
        <p class="page-subtitle mt-3">
          Создай карточку вакансии, стажировки, менторской программы или карьерного события.
        </p>
      </div>

      <UiAlert v-if="employerStore.errorMessage" class="mb-5">
        {{ employerStore.errorMessage }}
      </UiAlert>

      <UiAlert v-if="employerStore.successMessage" variant="success" class="mb-5">
        {{ employerStore.successMessage }}
      </UiAlert>

      <UiCard className="p-8">
        <form class="grid gap-5 md:grid-cols-2" @submit.prevent="onSubmit">
          <UiField label="Название" :error="errors.title">
            <UiInput v-model="form.title" placeholder="Junior Frontend Developer" />
          </UiField>

          <UiField label="Тип">
            <select
              v-model="form.type"
              class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
            >
              <option value="vacancy">Вакансия</option>
              <option value="internship">Стажировка</option>
              <option value="mentorship">Менторство</option>
              <option value="event">Событие</option>
            </select>
          </UiField>

          <div class="md:col-span-2">
            <UiField label="Короткое описание">
              <UiInput v-model="form.short_description" placeholder="Краткое описание возможности" />
            </UiField>
          </div>

          <div class="md:col-span-2">
            <UiField label="Полное описание" :error="errors.full_description">
              <textarea
                v-model="form.full_description"
                rows="6"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                placeholder="Расскажи подробнее о задачах, требованиях и формате участия"
              />
            </UiField>
          </div>

          <UiField label="Формат работы">
            <select
              v-model="form.work_format"
              class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
            >
              <option value="office">Офис</option>
              <option value="remote">Удаленно</option>
              <option value="hybrid">Гибрид</option>
              <option value="online">Онлайн</option>
            </select>
          </UiField>

          <UiField label="Тип занятости">
            <select
              v-model="form.employment_type"
              class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
            >
              <option value="">Не указан</option>
              <option value="full_time">Полная занятость</option>
              <option value="part_time">Частичная занятость</option>
              <option value="project">Проектная</option>
              <option value="flexible">Гибкая</option>
            </select>
          </UiField>

          <UiField label="Уровень">
            <select
              v-model="form.level"
              class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
            >
              <option value="">Не указан</option>
              <option value="trainee">Trainee</option>
              <option value="junior">Junior</option>
              <option value="middle">Middle</option>
              <option value="senior">Senior</option>
            </select>
          </UiField>

          <UiField label="Город">
            <UiInput v-model="form.city" placeholder="Москва" />
          </UiField>

          <UiField label="Адрес">
            <UiInput v-model="form.address" placeholder="ул. Тверская, 1" />
          </UiField>

          <UiField label="Ссылка">
            <UiInput v-model="form.external_url" placeholder="https://example.com/jobs/1" />
          </UiField>

          <UiField label="Контакт">
            <UiInput v-model="form.contacts_text" placeholder="hr@example.com" />
          </UiField>

          <UiField label="Зарплата от">
            <UiInput v-model="form.salary_from" type="number" placeholder="80000" />
          </UiField>

          <UiField label="Зарплата до">
            <UiInput v-model="form.salary_to" type="number" placeholder="120000" />
          </UiField>

          <UiField label="Дата завершения публикации">
            <UiInput v-model="form.expires_at" type="date" />
          </UiField>

          <UiField label="Дата события">
            <UiInput v-model="form.event_date" type="date" />
          </UiField>

          <div class="md:col-span-2">
            <UiField label="Теги">
              <div class="flex flex-wrap gap-2">
                <label
                  v-for="tag in employerStore.tags"
                  :key="tag.id"
                  class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700"
                >
                  <input
                    :checked="form.tag_ids.includes(tag.id)"
                    type="checkbox"
                    @change="toggleTag(tag.id)"
                  />
                  <span>{{ tag.name }}</span>
                </label>
              </div>
            </UiField>
          </div>

          <div class="md:col-span-2 flex items-center justify-between gap-4 border-t border-slate-200 pt-6">
            <label class="inline-flex items-center gap-2 text-sm text-slate-700">
              <input v-model="form.is_remote" type="checkbox" />
              <span>Удаленная возможность</span>
            </label>

            <UiButton type="submit" :disabled="employerStore.isSubmitting">
              {{ employerStore.isSubmitting ? 'Создание...' : 'Создать черновик' }}
            </UiButton>
          </div>
        </form>
      </UiCard>
    </UiContainer>
  </section>
</template>

<script setup lang="ts">
import { onMounted, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { ZodError } from 'zod';
import UiAlert from '@/shared/ui/UiAlert.vue';
import UiButton from '@/shared/ui/UiButton.vue';
import UiCard from '@/shared/ui/UiCard.vue';
import UiContainer from '@/shared/ui/UiContainer.vue';
import UiField from '@/shared/ui/UiField.vue';
import UiInput from '@/shared/ui/UiInput.vue';
import { useEmployerStore } from '@/features/employer/model/employer.store';
import { createEmployerOpportunitySchema } from '@/features/employer/lib/employer.schemas';

const router = useRouter();
const employerStore = useEmployerStore();

const form = reactive({
  title: '',
  short_description: '',
  full_description: '',
  type: 'vacancy' as 'vacancy' | 'internship' | 'mentorship' | 'event',
  work_format: 'office' as 'office' | 'remote' | 'hybrid' | 'online',
  employment_type: '',
  level: '',
  city: '',
  address: '',
  latitude: '',
  longitude: '',
  is_remote: false,
  expires_at: '',
  event_date: '',
  salary_from: '',
  salary_to: '',
  contacts_text: '',
  external_url: '',
  tag_ids: [] as number[],
});

const errors = reactive({
  title: '',
  full_description: '',
});

onMounted(async () => {
  await employerStore.loadTags();
});

async function onSubmit(): Promise<void> {
  employerStore.clearMessages();
  resetErrors();

  try {
    createEmployerOpportunitySchema.parse({
      title: form.title,
      full_description: form.full_description,
      type: form.type,
      work_format: form.work_format,
      is_remote: form.is_remote,
    });

    await employerStore.createOpportunity({
      title: form.title.trim(),
      short_description: nullable(form.short_description),
      full_description: form.full_description.trim(),
      type: form.type,
      work_format: form.work_format,
      employment_type: nullable(form.employment_type) as 'full_time' | 'part_time' | 'project' | 'flexible' | null,
      level: nullable(form.level) as 'junior' | 'middle' | 'senior' | 'trainee' | null,
      city: nullable(form.city),
      address: nullable(form.address),
      latitude: nullableNumber(form.latitude),
      longitude: nullableNumber(form.longitude),
      is_remote: form.is_remote,
      expires_at: nullable(form.expires_at),
      event_date: nullable(form.event_date),
      salary_from: nullableNumber(form.salary_from),
      salary_to: nullableNumber(form.salary_to),
      contacts_text: nullable(form.contacts_text),
      external_url: nullable(form.external_url),
      tag_ids: form.tag_ids,
    });

    await router.push('/employer/opportunities');
  } catch (error: unknown) {
    if (error instanceof ZodError) {
      applyZodErrors(error);
    }
  }
}

function toggleTag(tagId: number): void {
  if (form.tag_ids.includes(tagId)) {
    form.tag_ids = form.tag_ids.filter((id) => id !== tagId);
    return;
  }

  form.tag_ids = [...form.tag_ids, tagId];
}

function resetErrors(): void {
  errors.title = '';
  errors.full_description = '';
}

function applyZodErrors(error: ZodError): void {
  for (const issue of error.issues) {
    const path = issue.path[0];
    if (path === 'title' || path === 'full_description') {
      errors[path] = issue.message;
    }
  }
}

function nullable(value: string): string | null {
  const normalized = value.trim();
  return normalized.length > 0 ? normalized : null;
}

function nullableNumber(value: string): number | null {
  const normalized = value.trim();

  if (normalized.length === 0) {
    return null;
  }

  const parsed = Number(normalized);
  return Number.isNaN(parsed) ? null : parsed;
}
</script>
