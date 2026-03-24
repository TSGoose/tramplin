
<template>
  <section class="page-section">
    <UiContainer className="max-w-4xl">
      <div class="mb-8 flex items-start justify-between gap-4">
        <div>
          <h1 class="text-3xl font-semibold tracking-tight text-slate-900">Профиль студента</h1>
          <p class="mt-2 text-sm leading-6 text-slate-600">
            Заполните карьерный профиль, чтобы в дальнейшем использовать его для откликов, рекомендаций и подбора возможностей.
          </p>
        </div>

        <UiBadge variant="default">
          {{ moderationLabel }}
        </UiBadge>
      </div>

      <UiAlert v-if="profileStore.errorMessage" class="mb-5">
        {{ profileStore.errorMessage }}
      </UiAlert>

      <UiAlert v-if="profileStore.successMessage" variant="success" class="mb-5">
        {{ profileStore.successMessage }}
      </UiAlert>

      <UiCard v-if="profileStore.isLoading" className="p-8">
        <p class="text-sm text-slate-600">Загрузка профиля...</p>
      </UiCard>

      <UiCard v-else className="p-8">
        <form class="grid gap-5 md:grid-cols-2" @submit.prevent="onSubmit">
          <UiField label="ФИО">
            <UiInput v-model="form.full_name" placeholder="Иван Петров" />
          </UiField>

          <UiField label="ВУЗ">
            <UiInput v-model="form.university_name" placeholder="Название университета" />
          </UiField>

          <UiField label="Курс">
            <UiInput v-model="form.course" type="number" placeholder="3" />
          </UiField>

          <UiField label="Год выпуска">
            <UiInput v-model="form.graduation_year" type="number" placeholder="2027" />
          </UiField>

          <UiField label="Портфолио">
            <UiInput v-model="form.portfolio_url" placeholder="https://portfolio.example.com" />
          </UiField>

          <UiField label="GitHub">
            <UiInput v-model="form.github_url" placeholder="https://github.com/username" />
          </UiField>

          <div class="md:col-span-2">
            <UiField label="О себе">
              <textarea
                v-model="form.about"
                rows="5"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                placeholder="Кратко расскажите о себе, интересах и карьерных целях"
              />
            </UiField>
          </div>

          <UiField label="Видимость профиля">
            <select
              v-model="form.privacy_level"
              class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
            >
              <option value="platform_visible">Виден всем авторизованным</option>
              <option value="contacts_only">Виден только контактам</option>
              <option value="private">Только мне</option>
            </select>
          </UiField>

          <UiField label="Предпочтительный формат работы">
            <div class="flex flex-wrap gap-2">
              <label
                v-for="format in workFormatOptions"
                :key="format.value"
                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700"
              >
                <input
                  :checked="form.preferred_work_formats.includes(format.value)"
                  type="checkbox"
                  @change="toggleWorkFormat(format.value)"
                />
                <span>{{ format.label }}</span>
              </label>
            </div>
          </UiField>

          <UiField label="Предпочитаемые города">
            <UiInput
              v-model="citiesInput"
              placeholder="Москва, Санкт-Петербург, Казань"
            />
          </UiField>

          <div class="md:col-span-2 flex items-center justify-between gap-4 border-t border-slate-200 pt-6">
            <p class="text-sm text-slate-500">
              Просмотров профиля: {{ profileStore.profile?.profile_views_count ?? 0 }}
            </p>

            <UiButton type="submit" :disabled="profileStore.isSaving">
              {{ profileStore.isSaving ? 'Сохранение...' : 'Сохранить профиль' }}
            </UiButton>
          </div>
        </form>
      </UiCard>
    </UiContainer>
  </section>
</template>

<script setup lang="ts">
import { computed, onMounted, reactive, ref, watch } from 'vue';
import UiAlert from '@/shared/ui/UiAlert.vue';
import UiBadge from '@/shared/ui/UiBadge.vue';
import UiButton from '@/shared/ui/UiButton.vue';
import UiCard from '@/shared/ui/UiCard.vue';
import UiContainer from '@/shared/ui/UiContainer.vue';
import UiField from '@/shared/ui/UiField.vue';
import UiInput from '@/shared/ui/UiInput.vue';
import { useApplicantProfileStore } from '@/features/applicant-profile/model/applicantProfile.store';
import type { ProfilePrivacyLevel } from '@/entities/applicant/model/types';

const profileStore = useApplicantProfileStore();

const form = reactive({
  full_name: '',
  university_name: '',
  course: '',
  graduation_year: '',
  about: '',
  portfolio_url: '',
  github_url: '',
  privacy_level: 'platform_visible' as ProfilePrivacyLevel,
  preferred_work_formats: [] as string[],
});

const citiesInput = ref('');

const workFormatOptions = [
  { value: 'remote', label: 'Удаленно' },
  { value: 'hybrid', label: 'Гибрид' },
  { value: 'office', label: 'Офис' },
  { value: 'part_time', label: 'Частичная занятость' },
];

const moderationLabel = computed<string>(() => {
  const status = profileStore.profile?.moderation_status;

  switch (status) {
    case 'approved':
      return 'Профиль подтвержден';
    case 'needs_revision':
      return 'Требуются правки';
    case 'rejected':
      return 'Профиль отклонен';
    default:
      return 'На модерации';
  }
});

onMounted(async () => {
  await profileStore.fetchProfile();
  syncFormFromProfile();
});

watch(
  () => profileStore.profile,
  () => {
    syncFormFromProfile();
  },
);

function syncFormFromProfile(): void {
  const profile = profileStore.profile;

  if (!profile) {
    return;
  }

  form.full_name = profile.full_name ?? '';
  form.university_name = profile.university_name ?? '';
  form.course = profile.course ? String(profile.course) : '';
  form.graduation_year = profile.graduation_year ? String(profile.graduation_year) : '';
  form.about = profile.about ?? '';
  form.portfolio_url = profile.portfolio_url ?? '';
  form.github_url = profile.github_url ?? '';
  form.privacy_level = profile.privacy_level;
  form.preferred_work_formats = [...profile.preferred_work_formats];
  citiesInput.value = profile.preferred_cities.join(', ');
}

function toggleWorkFormat(value: string): void {
  if (form.preferred_work_formats.includes(value)) {
    form.preferred_work_formats = form.preferred_work_formats.filter((item) => item !== value);
    return;
  }

  form.preferred_work_formats = [...form.preferred_work_formats, value];
}

async function onSubmit(): Promise<void> {
  profileStore.clearMessages();

  await profileStore.saveProfile({
    full_name: normalizeNullableString(form.full_name),
    university_name: normalizeNullableString(form.university_name),
    course: normalizeNullableNumber(form.course),
    graduation_year: normalizeNullableNumber(form.graduation_year),
    about: normalizeNullableString(form.about),
    portfolio_url: normalizeNullableString(form.portfolio_url),
    github_url: normalizeNullableString(form.github_url),
    privacy_level: form.privacy_level,
    preferred_work_formats: form.preferred_work_formats,
    preferred_cities: citiesInput.value
      .split(',')
      .map((item) => item.trim())
      .filter(Boolean),
  });
}

function normalizeNullableString(value: string): string | null {
  const normalized = value.trim();
  return normalized.length > 0 ? normalized : null;
}

function normalizeNullableNumber(value: string): number | null {
  const normalized = value.trim();

  if (normalized.length === 0) {
    return null;
  }

  const parsed = Number(normalized);
  return Number.isNaN(parsed) ? null : parsed;
}
</script>
