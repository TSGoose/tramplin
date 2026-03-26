<template>
  <section class="page-section">
    <UiContainer>
      <div class="mb-8">
        <h1 class="page-title">Возможности на модерации</h1>
        <p class="page-subtitle mt-3">
          Проверка карточек возможностей перед публикацией в публичном каталоге.
        </p>
      </div>

      <UiAlert v-if="curatorStore.errorMessage" class="mb-5">
        {{ curatorStore.errorMessage }}
      </UiAlert>

      <UiAlert v-if="curatorStore.successMessage" variant="success" class="mb-5">
        {{ curatorStore.successMessage }}
      </UiAlert>

      <div v-if="curatorStore.isOpportunitiesLoading" class="space-y-4">
        <UiCard v-for="index in 3" :key="index" className="p-6">
          <div class="space-y-3">
            <div class="h-4 w-24 animate-pulse rounded bg-slate-200" />
            <div class="h-6 w-1/2 animate-pulse rounded bg-slate-200" />
            <div class="h-4 w-full animate-pulse rounded bg-slate-200" />
          </div>
        </UiCard>
      </div>

      <div v-else-if="curatorStore.opportunities.length === 0">
        <UiCard className="p-8 text-center">
          <h2 class="text-lg font-semibold text-slate-900">Очередь пуста</h2>
          <p class="mt-2 text-sm text-slate-600">
            Сейчас нет карточек, ожидающих проверки.
          </p>
        </UiCard>
      </div>

      <div v-else class="space-y-4">
        <UiCard
          v-for="opportunity in curatorStore.opportunities"
          :key="opportunity.id"
          className="p-6"
        >
          <div class="grid gap-6 lg:grid-cols-[1fr_320px]">
            <div>
              <div class="flex items-start justify-between gap-4">
                <div>
                  <p class="text-xs uppercase tracking-wide text-slate-500">{{ typeLabel(opportunity.type) }}</p>
                  <h2 class="mt-2 text-xl font-semibold text-slate-900">{{ opportunity.title }}</h2>
                  <p class="mt-2 text-sm text-slate-600">{{ opportunity.company.name }}</p>
                </div>

                <UiBadge>{{ opportunityStatusLabel(opportunity.status) }}</UiBadge>
              </div>

              <p class="mt-4 text-sm leading-6 text-slate-700">
                {{ opportunity.full_description }}
              </p>

              <div class="mt-4 flex flex-wrap gap-2">
                <UiBadge v-if="opportunity.city">{{ opportunity.city }}</UiBadge>
                <UiBadge v-if="opportunity.work_format" variant="warning">
                  {{ workFormatLabel(opportunity.work_format) }}
                </UiBadge>
                <UiBadge v-for="tag in opportunity.tags" :key="tag.id">
                  {{ tag.name }}
                </UiBadge>
              </div>
            </div>

            <ModerationActionPanel
              :disabled="curatorStore.isSubmitting"
              @approve="onApprove(opportunity.id, $event)"
              @needs-revision="onNeedsRevision(opportunity.id, $event)"
              @reject="onReject(opportunity.id, $event)"
            />
          </div>
        </UiCard>
      </div>
    </UiContainer>
  </section>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import type { Opportunity } from '@/entities/opportunity/model/types';
import { useCuratorStore } from '@/features/curator/model/curator.store';
import ModerationActionPanel from '@/widgets/curator/ModerationActionPanel.vue';
import UiAlert from '@/shared/ui/UiAlert.vue';
import UiBadge from '@/shared/ui/UiBadge.vue';
import UiCard from '@/shared/ui/UiCard.vue';
import UiContainer from '@/shared/ui/UiContainer.vue';

const curatorStore = useCuratorStore();

onMounted(async () => {
  await Promise.all([
    curatorStore.loadOpportunities(),
    curatorStore.loadAuditLogs(),
  ]);
});

async function onApprove(opportunityId: number, comment: string | null): Promise<void> {
  curatorStore.clearMessages();
  await curatorStore.moderateOpportunity(opportunityId, {
    action: 'approve',
    comment,
  });
}

async function onNeedsRevision(opportunityId: number, comment: string | null): Promise<void> {
  curatorStore.clearMessages();
  await curatorStore.moderateOpportunity(opportunityId, {
    action: 'needs_revision',
    comment,
  });
}

async function onReject(opportunityId: number, comment: string | null): Promise<void> {
  curatorStore.clearMessages();
  await curatorStore.moderateOpportunity(opportunityId, {
    action: 'reject',
    comment,
  });
}

function typeLabel(type: Opportunity['type']): string {
  switch (type) {
    case 'internship':
      return 'Стажировка';
    case 'mentorship':
      return 'Менторство';
    case 'event':
      return 'Событие';
    default:
      return 'Вакансия';
  }
}

function workFormatLabel(workFormat: Opportunity['work_format']): string {
  switch (workFormat) {
    case 'remote':
      return 'Удаленно';
    case 'hybrid':
      return 'Гибрид';
    case 'online':
      return 'Онлайн';
    default:
      return 'Офис';
  }
}

function opportunityStatusLabel(status: Opportunity['status']): string {
  switch (status) {
    case 'pending_moderation':
      return 'На модерации';
    case 'needs_revision':
      return 'Нужны правки';
    case 'published':
      return 'Опубликовано';
    case 'rejected':
      return 'Отклонено';
    default:
      return 'Черновик';
  }
}
</script>
