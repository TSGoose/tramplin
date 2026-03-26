<template>
  <UiCard className="p-6">
    <div class="space-y-4">
      <div>
        <p class="text-xs uppercase tracking-wide text-slate-500">{{ typeLabel }}</p>
        <h3 class="mt-2 text-xl font-semibold text-slate-900">{{ opportunity.title }}</h3>
        <p class="mt-2 text-sm text-slate-600">{{ opportunity.company.name }}</p>
      </div>

      <p class="text-sm leading-6 text-slate-700">
        {{ opportunity.short_description || 'Описание пока не указано.' }}
      </p>

      <div class="flex flex-wrap gap-2">
        <UiBadge v-if="opportunity.city">{{ opportunity.city }}</UiBadge>
        <UiBadge v-if="opportunity.work_format" variant="warning">{{ workFormatLabel }}</UiBadge>
      </div>

      <RouterLink :to="`/opportunities/${opportunity.id}`">
        <UiButton variant="secondary">Открыть карточку</UiButton>
      </RouterLink>
    </div>
  </UiCard>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import type { Opportunity } from '@/entities/opportunity/model/types';
import UiBadge from '@/shared/ui/UiBadge.vue';
import UiButton from '@/shared/ui/UiButton.vue';
import UiCard from '@/shared/ui/UiCard.vue';

const props = defineProps<{
  opportunity: Opportunity;
}>();

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
</script>
