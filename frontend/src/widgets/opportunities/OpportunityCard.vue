<template>
  <UiCard className="p-6">
    <div class="flex flex-col gap-4">
      <div class="flex items-start justify-between gap-4">
        <div>
          <p class="text-xs uppercase tracking-wide text-slate-500">
            {{ typeLabel }}
          </p>
          <h3 class="mt-1 text-lg font-semibold text-slate-900">
            {{ opportunity.title }}
          </h3>
          <p class="mt-2 text-sm text-slate-600">
            {{ opportunity.company.name }}
          </p>
        </div>

        <UiBadge>
          {{ workFormatLabel }}
        </UiBadge>
      </div>

      <p class="text-sm leading-6 text-slate-600">
        {{ opportunity.short_description || fallbackDescription }}
      </p>

      <div class="flex flex-wrap gap-2">
        <UiBadge v-if="opportunity.level">
          {{ levelLabel }}
        </UiBadge>

        <UiBadge v-if="opportunity.city">
          {{ opportunity.city }}
        </UiBadge>

        <UiBadge v-for="tag in visibleTags" :key="tag.id" variant="warning">
          {{ tag.name }}
        </UiBadge>
      </div>

    <div class="flex items-center justify-between gap-4 pt-2">
        <div class="text-sm font-medium text-slate-900">
          {{ salaryLabel }}
        </div>

        <div class="flex items-center gap-2">
          <FavoriteButton
            v-if="canUseApplicantActions"
            :opportunity-id="opportunity.id"
          />
          <RouterLink :to="`/opportunities/${opportunity.id}`">
            <UiButton variant="secondary">Подробнее</UiButton>
          </RouterLink>
        </div>
      </div>
    </div>
  </UiCard>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useAuthStore } from '@/features/auth/model/auth.store';
import FavoriteButton from '@/widgets/opportunities/FavoriteButton.vue';

import type { Opportunity } from '@/entities/opportunity/model/types';
import UiBadge from '@/shared/ui/UiBadge.vue';
import UiButton from '@/shared/ui/UiButton.vue';
import UiCard from '@/shared/ui/UiCard.vue';

const props = defineProps<{
  opportunity: Opportunity;
}>();

const authStore = useAuthStore();

const canUseApplicantActions = computed(() => authStore.user?.role === 'applicant');

const visibleTags = computed(() => props.opportunity.tags.slice(0, 3));

const fallbackDescription = computed(() => {
  return 'Карьерная возможность для студентов и выпускников.';
});

const typeLabel = computed(() => {
  switch (props.opportunity.type) {
    case 'internship':
      return 'Стажировка';
    case 'mentorship':
      return 'Менторство';
    case 'event':
      return 'Событие';
    default:
      return 'Вакансия';
  }
});

const workFormatLabel = computed(() => {
  switch (props.opportunity.work_format) {
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
  switch (props.opportunity.level) {
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
  const from = props.opportunity.salary_from;
  const to = props.opportunity.salary_to;

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
</script>
