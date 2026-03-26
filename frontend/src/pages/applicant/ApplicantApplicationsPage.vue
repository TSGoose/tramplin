<template>
  <section class="page-section">
    <UiContainer>
      <div class="mb-8">
        <h1 class="page-title">Мои отклики</h1>
        <p class="page-subtitle mt-3">
          История откликов по вакансиям, стажировкам и карьерным событиям.
        </p>
      </div>

      <UiAlert v-if="applicationsStore.errorMessage" class="mb-5">
        {{ applicationsStore.errorMessage }}
      </UiAlert>

      <div v-if="applicationsStore.isLoading" class="space-y-4">
        <UiCard v-for="index in 3" :key="index" className="p-6">
          <div class="space-y-3">
            <div class="h-4 w-24 animate-pulse rounded bg-slate-200" />
            <div class="h-6 w-1/2 animate-pulse rounded bg-slate-200" />
            <div class="h-4 w-full animate-pulse rounded bg-slate-200" />
          </div>
        </UiCard>
      </div>

      <div v-else-if="applicationsStore.items.length === 0">
        <UiCard className="p-8 text-center">
          <h2 class="text-lg font-semibold text-slate-900">Откликов пока нет</h2>
          <p class="mt-2 text-sm text-slate-600">
            Когда отправишь первый отклик, он появится здесь.
          </p>
        </UiCard>
      </div>

      <div v-else class="space-y-4">
        <UiCard
          v-for="item in applicationsStore.items"
          :key="item.id"
          className="p-6"
        >
          <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
            <div>
              <p class="text-xs uppercase tracking-wide text-slate-500">
                {{ formatApplicationStatus(item.status) }}
              </p>
              <h2 class="mt-2 text-xl font-semibold text-slate-900">
                {{ item.opportunity.title }}
              </h2>
              <p class="mt-2 text-sm text-slate-600">
                {{ item.opportunity.company.name }}
              </p>
              <p class="mt-3 text-sm leading-6 text-slate-600">
                {{ item.cover_letter || 'Сопроводительное письмо не добавлено.' }}
              </p>
            </div>

            <div class="min-w-[220px] space-y-2">
              <UiBadge>{{ item.opportunity.city || 'Город не указан' }}</UiBadge>
              <p class="text-sm text-slate-500">
                Отправлен: {{ formatDate(item.created_at) }}
              </p>
              <RouterLink :to="`/opportunities/${item.opportunity.id}`">
                <UiButton variant="secondary">Открыть карточку</UiButton>
              </RouterLink>
            </div>
          </div>
        </UiCard>
      </div>
    </UiContainer>
  </section>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import type { ApplicationStatus } from '@/entities/application/model/types';
import { useApplicationsStore } from '@/features/applications/model/applications.store';
import UiAlert from '@/shared/ui/UiAlert.vue';
import UiBadge from '@/shared/ui/UiBadge.vue';
import UiButton from '@/shared/ui/UiButton.vue';
import UiCard from '@/shared/ui/UiCard.vue';
import UiContainer from '@/shared/ui/UiContainer.vue';

const applicationsStore = useApplicationsStore();

onMounted(async () => {
  await applicationsStore.loadApplications();
});

function formatApplicationStatus(status: ApplicationStatus): string {
  switch (status) {
    case 'reviewing':
      return 'На рассмотрении';
    case 'interview':
      return 'Интервью';
    case 'reserve':
      return 'Резерв';
    case 'accepted':
      return 'Принят';
    case 'rejected':
      return 'Отклонен';
    default:
      return 'Новый отклик';
  }
}

function formatDate(value: string | null): string {
  if (!value) {
    return '—';
  }

  return new Date(value).toLocaleString('ru-RU');
}
</script>
