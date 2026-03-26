<template>
  <section class="page-section">
    <UiContainer className="max-w-5xl">
      <div class="mb-8">
        <h1 class="page-title">Редактирование возможности</h1>
        <p class="page-subtitle mt-3">
          Исправь карточку и отправь ее повторно на модерацию.
        </p>
      </div>

      <UiAlert v-if="employerStore.errorMessage" class="mb-5">
        {{ employerStore.errorMessage }}
      </UiAlert>

      <UiAlert v-if="employerStore.successMessage" variant="success" class="mb-5">
        {{ employerStore.successMessage }}
      </UiAlert>

      <UiCard
        v-if="opportunity?.moderation_comment"
        className="mb-6 border border-amber-200 bg-amber-50 p-6"
      >
        <h2 class="text-lg font-semibold text-amber-900">Замечание куратора</h2>
        <p class="mt-2 text-sm leading-6 text-amber-800">
          {{ opportunity.moderation_comment }}
        </p>
      </UiCard>

      <UiCard v-if="employerStore.isSelectedOpportunityLoading" className="p-8">
        <p class="text-sm text-slate-600">Загрузка карточки...</p>
      </UiCard>

      <UiCard v-else className="p-8">
        <form class="grid gap-5 md:grid-cols-2" @submit.prevent="onSave">
          <UiField label="Название">
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
            <UiField label="Полное описание">
              <textarea
                v-model="form.full_description"
                rows="6"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
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

            <div class="flex items-center gap-3">
              <UiButton variant="secondary" :disabled="employerStore.isSubmitting" @click.prevent="onResubmit">
                Отправить повторно
              </UiButton>
              <UiButton type="submit" :disabled="employerStore.isSubmitting">
                Сохранить изменения
              </UiButton>
            </div>
          </div>
        </form>
      </UiCard>
    </UiContainer>
  </section>
</template>

<script setup lang="ts">
import { onMounted, reactive, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import UiAlert from '@/shared/ui/UiAlert.vue';
import UiButton from '@/shared/ui/UiButton.vue';
import UiCard from '@/shared/ui/UiCard.vue';
import UiContainer from '@/shared/ui/UiContainer.vue';
import UiField from '@/shared/ui/UiField.vue';
import UiInput from '@/shared/ui/UiInput.vue';
import { useEmployerStore } from '@/features/employer/model/employer.store';

const route = useRoute();
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

const opportunity = computed(() => employerStore.selectedOpportunity);
const opportunityId = computed(() => Number(route.params.id));

onMounted(async () => {
  await Promise.all([
    employerStore.loadTags(),
    employerStore.loadOpportunity(opportunityId.value),
  ]);

  syncForm();
});

function syncForm(): void {
  const item = employerStore.selectedOpportunity;

  if (!item) {
    return;
  }

  form.title = item.title ?? '';
  form.short_description = item.short_description ?? '';
  form.full_description = item.full_description ?? '';
  form.type = (item.type ?? 'vacancy') as 'vacancy' | 'internship' | 'mentorship' | 'event';
  form.work_format = (item.work_format ?? 'office') as 'office' | 'remote' | 'hybrid' | 'online';
  form.employment_type = item.employment_type ?? '';
  form.level = item.level ?? '';
  form.city = item.city ?? '';
  form.address = item.address ?? '';
  form.latitude = item.latitude ? String(item.latitude) : '';
  form.longitude = item.longitude ? String(item.longitude) : '';
  form.is_remote = item.is_remote;
  form.expires_at = item.expires_at ? item.expires_at.slice(0, 10) : '';
  form.event_date = item.event_date ? item.event_date.slice(0, 10) : '';
  form.salary_from = item.salary_from ? String(item.salary_from) : '';
  form.salary_to = item.salary_to ? String(item.salary_to) : '';
  form.contacts_text = item.contacts_text ?? '';
  form.external_url = item.external_url ?? '';
  form.tag_ids = item.tags.map((tag) => tag.id);
}

async function onSave(): Promise<void> {
  employerStore.clearMessages();

  await employerStore.saveOpportunity(opportunityId.value, {
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
}

async function onResubmit(): Promise<void> {
  employerStore.clearMessages();
  await onSave();
  await employerStore.sendOpportunityToModeration(opportunityId.value);
  await router.push('/employer/opportunities');
}

function toggleTag(tagId: number): void {
  if (form.tag_ids.includes(tagId)) {
    form.tag_ids = form.tag_ids.filter((id) => id !== tagId);
    return;
  }

  form.tag_ids = [...form.tag_ids, tagId];
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
