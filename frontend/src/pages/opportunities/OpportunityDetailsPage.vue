<template>
  <section class="page-section">
    <UiContainer className="max-w-5xl">
      <RouterLink
        to="/opportunities"
        class="mb-6 inline-flex text-sm font-medium text-blue-600 transition hover:text-blue-700"
      >
        ← Назад к каталогу
      </RouterLink>

      <UiAlert v-if="catalogStore.errorMessage" class="mb-5">
        {{ catalogStore.errorMessage }}
      </UiAlert>

      <UiCard v-if="catalogStore.isDetailsLoading" className="p-8">
        <p class="text-sm text-slate-600">Загрузка карточки возможности...</p>
      </UiCard>

      <UiCard v-else-if="opportunity" className="p-8">
        <div class="flex flex-col gap-6">
          <div class="flex flex-wrap items-start justify-between gap-6">
            <div>
              <p class="text-xs uppercase tracking-wide text-slate-500">{{ typeLabel }}</p>
              <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">
                {{ opportunity.title }}
              </h1>
              <p class="mt-3 text-base text-slate-600">
                {{ opportunity.company.name }}
              </p>
            </div>

            <div class="flex flex-wrap gap-2">
              <UiBadge>{{ workFormatLabel }}</UiBadge>
              <UiBadge v-if="opportunity.level" variant="warning">{{ levelLabel }}</UiBadge>
              <UiBadge v-if="opportunity.city">{{ opportunity.city }}</UiBadge>
            </div>
          </div>

          <div class="grid gap-6 lg:grid-cols-[1fr_320px]">
            <div class="space-y-6">
              <div>
                <h2 class="text-lg font-semibold text-slate-900">Описание</h2>
                <p class="mt-3 whitespace-pre-line text-sm leading-7 text-slate-700">
                  {{ opportunity.full_description }}
                </p>
              </div>

              <div v-if="opportunity.tags.length > 0">
                <h2 class="text-lg font-semibold text-slate-900">Теги</h2>
                <div class="mt-3 flex flex-wrap gap-2">
                  <UiBadge v-for="tag in opportunity.tags" :key="tag.id">
                    {{ tag.name }}
                  </UiBadge>
                </div>
              </div>
            </div>

            <UiCard className="p-6">
              <div class="space-y-4">
                <div>
                  <p class="text-xs uppercase tracking-wide text-slate-500">Оплата</p>
                  <p class="mt-1 text-lg font-semibold text-slate-900">{{ salaryLabel }}</p>
                </div>

                <div v-if="opportunity.event_date">
                  <p class="text-xs uppercase tracking-wide text-slate-500">Дата события</p>
                  <p class="mt-1 text-sm text-slate-700">{{ formattedEventDate }}</p>
                </div>

                <div v-if="opportunity.address">
                  <p class="text-xs uppercase tracking-wide text-slate-500">Адрес</p>
                  <p class="mt-1 text-sm text-slate-700">{{ opportunity.address }}</p>
                </div>

                <div v-if="opportunity.contacts_text">
                  <p class="text-xs uppercase tracking-wide text-slate-500">Контакт</p>
                  <p class="mt-1 text-sm text-slate-700">{{ opportunity.contacts_text }}</p>
                </div>

                <div class="space-y-3">
                  <FavoriteButton
                    v-if="canUseApplicantActions && opportunity"
                    :opportunity-id="opportunity.id"
                  />

                  <a
                    v-if="opportunity.external_url"
                    :href="opportunity.external_url"
                    target="_blank"
                    rel="noreferrer"
                  >
                    <UiButton fullWidth>Открыть внешний ресурс</UiButton>
                  </a>

                  <UiButton v-if="!opportunity.external_url" fullWidth disabled>
                    Отклик будет доступен позже
                  </UiButton>
                  </div>
              </div>
            </UiCard>
          </div>
        </div>
      </UiCard>
      <div v-if="canUseApplicantActions && opportunity" class="mt-6">
        <ApplyDialog :opportunity-id="opportunity.id" />
      </div>
    </UiContainer>
  </section>
</template>

<script setup lang="ts">
import { computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useFavoritesStore } from '@/features/favorites/model/favorites.store';
import UiAlert from '@/shared/ui/UiAlert.vue';
import UiBadge from '@/shared/ui/UiBadge.vue';
import UiButton from '@/shared/ui/UiButton.vue';
import UiCard from '@/shared/ui/UiCard.vue';
import UiContainer from '@/shared/ui/UiContainer.vue';
import { useAuthStore } from '@/features/auth/model/auth.store';
import { useApplicationsStore } from '@/features/applications/model/applications.store';
import FavoriteButton from '@/widgets/opportunities/FavoriteButton.vue';
import ApplyDialog from '@/widgets/opportunities/ApplyDialog.vue';
import { useOpportunityCatalogStore } from '@/features/opportunity-catalog/model/opportunityCatalog.store';

const route = useRoute();
const catalogStore = useOpportunityCatalogStore();

const opportunity = computed(() => catalogStore.selectedOpportunity);

const authStore = useAuthStore();
const applicationsStore = useApplicationsStore();

const canUseApplicantActions = computed(() => authStore.user?.role === 'applicant');

onMounted(async () => {
  await catalogStore.loadOpportunity(String(route.params.id));

  if (authStore.user?.role === 'applicant') {
    await applicationsStore.loadApplications();
  }
});


const favoritesStore = useFavoritesStore();

onMounted(async () => {
  await catalogStore.loadOpportunity(String(route.params.id));

  if (authStore.user?.role === 'applicant') {
    await Promise.all([
      applicationsStore.loadApplications(),
      favoritesStore.loadFavorites(),
    ]);
  }
});

const typeLabel = computed(() => {
  switch (opportunity.value?.type) {
    case 'internship':
      return 'Стажировка';
    case 'mentorship':
      return 'Менторская программа';
    case 'event':
      return 'Карьерное событие';
    default:
      return 'Вакансия';
  }
});

const workFormatLabel = computed(() => {
  switch (opportunity.value?.work_format) {
    case 'remote':
      return 'Удаленно';
    case 'hybrid':
      return 'Гибрид';
    case 'online':
      return 'Онлайн';
    default:
      return 'Офис';
  }
});

const levelLabel = computed(() => {
  switch (opportunity.value?.level) {
    case 'trainee':
      return 'Trainee';
    case 'junior':
      return 'Junior';
    case 'middle':
      return 'Middle';
    case 'senior':
      return 'Senior';
    default:
      return 'Уровень не указан';
  }
});

const salaryLabel = computed(() => {
  const from = opportunity.value?.salary_from;
  const to = opportunity.value?.salary_to;

  if (from && to) {
    return `${from.toLocaleString('ru-RU')} – ${to.toLocaleString('ru-RU')} ₽`;
  }

  if (from) {
    return `от ${from.toLocaleString('ru-RU')} ₽`;
  }

  if (to) {
    return `до ${to.toLocaleString('ru-RU')} ₽`;
  }

  return 'Оплата по договоренности';
});

const formattedEventDate = computed(() => {
  if (!opportunity.value?.event_date) {
    return '';
  }

  return new Date(opportunity.value.event_date).toLocaleString('ru-RU');
});

onMounted(async () => {
  await catalogStore.loadOpportunity(String(route.params.id));
});
</script>
