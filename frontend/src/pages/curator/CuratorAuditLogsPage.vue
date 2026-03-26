<template>
  <section class="page-section">
    <UiContainer>
      <div class="mb-8">
        <h1 class="page-title">Журнал действий</h1>
        <p class="page-subtitle mt-3">
          История решений по модерации компаний и возможностей.
        </p>
      </div>

      <UiAlert v-if="curatorStore.errorMessage" class="mb-5">
        {{ curatorStore.errorMessage }}
      </UiAlert>

      <div v-if="curatorStore.isAuditLoading" class="space-y-4">
        <UiCard v-for="index in 4" :key="index" className="p-6">
          <div class="space-y-3">
            <div class="h-4 w-24 animate-pulse rounded bg-slate-200" />
            <div class="h-6 w-1/2 animate-pulse rounded bg-slate-200" />
            <div class="h-4 w-full animate-pulse rounded bg-slate-200" />
          </div>
        </UiCard>
      </div>

      <div v-else-if="curatorStore.auditLogs.length === 0">
        <UiCard className="p-8 text-center">
          <h2 class="text-lg font-semibold text-slate-900">История пока пуста</h2>
          <p class="mt-2 text-sm text-slate-600">
            После первых решений куратора журнал появится здесь.
          </p>
        </UiCard>
      </div>

      <div v-else class="space-y-4">
        <UiCard
          v-for="log in curatorStore.auditLogs"
          :key="log.id"
          className="p-6"
        >
          <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
            <div>
              <p class="text-xs uppercase tracking-wide text-slate-500">
                {{ log.entity_type }} #{{ log.entity_id }}
              </p>
              <h2 class="mt-2 text-lg font-semibold text-slate-900">
                {{ actionLabel(log.action) }}
              </h2>
              <p class="mt-2 text-sm text-slate-600">
                Исполнитель: {{ log.actor?.display_name || 'Система' }}
              </p>
              <p class="mt-2 text-sm leading-6 text-slate-700">
                {{ log.comment || 'Комментарий не указан.' }}
              </p>
            </div>

            <div class="space-y-2 text-sm text-slate-600">
              <p>
                <span class="font-medium text-slate-900">Было:</span>
                {{ log.old_status || '—' }}
              </p>
              <p>
                <span class="font-medium text-slate-900">Стало:</span>
                {{ log.new_status || '—' }}
              </p>
              <p>
                <span class="font-medium text-slate-900">Дата:</span>
                {{ formatDate(log.created_at) }}
              </p>
            </div>
          </div>
        </UiCard>
      </div>
    </UiContainer>
  </section>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { useCuratorStore } from '@/features/curator/model/curator.store';
import UiAlert from '@/shared/ui/UiAlert.vue';
import UiCard from '@/shared/ui/UiCard.vue';
import UiContainer from '@/shared/ui/UiContainer.vue';

const curatorStore = useCuratorStore();

onMounted(async () => {
  await curatorStore.loadAuditLogs();
});

function actionLabel(action: string): string {
  switch (action) {
    case 'approved':
      return 'Одобрено';
    case 'rejected':
      return 'Отклонено';
    case 'needs_revision':
      return 'Отправлено на доработку';
    default:
      return action;
  }
}

function formatDate(value: string | null): string {
  if (!value) {
    return '—';
  }

  return new Date(value).toLocaleString('ru-RU');
}
</script>
