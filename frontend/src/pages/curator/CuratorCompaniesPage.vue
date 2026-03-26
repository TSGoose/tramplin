<template>
  <section class="page-section">
    <UiContainer>
      <div class="mb-8">
        <h1 class="page-title">Компании на верификации</h1>
        <p class="page-subtitle mt-3">
          Проверка профилей работодателей перед публикацией их возможностей.
        </p>
      </div>

      <UiAlert v-if="curatorStore.errorMessage" class="mb-5">
        {{ curatorStore.errorMessage }}
      </UiAlert>

      <UiAlert v-if="curatorStore.successMessage" variant="success" class="mb-5">
        {{ curatorStore.successMessage }}
      </UiAlert>

      <div v-if="curatorStore.isCompaniesLoading" class="space-y-4">
        <UiCard v-for="index in 3" :key="index" className="p-6">
          <div class="space-y-3">
            <div class="h-4 w-24 animate-pulse rounded bg-slate-200" />
            <div class="h-6 w-1/2 animate-pulse rounded bg-slate-200" />
            <div class="h-4 w-full animate-pulse rounded bg-slate-200" />
          </div>
        </UiCard>
      </div>

      <div v-else-if="curatorStore.companies.length === 0">
        <UiCard className="p-8 text-center">
          <h2 class="text-lg font-semibold text-slate-900">Очередь пуста</h2>
          <p class="mt-2 text-sm text-slate-600">
            Сейчас нет компаний, ожидающих проверки.
          </p>
        </UiCard>
      </div>

      <div v-else class="space-y-4">
        <UiCard
          v-for="company in curatorStore.companies"
          :key="company.id"
          className="p-6"
        >
          <div class="grid gap-6 lg:grid-cols-[1fr_320px]">
            <div>
              <div class="flex items-start justify-between gap-4">
                <div>
                  <h2 class="text-xl font-semibold text-slate-900">{{ company.name }}</h2>
                  <p class="mt-2 text-sm text-slate-600">
                    {{ company.industry || 'Отрасль не указана' }}
                  </p>
                </div>

                <UiBadge>{{ companyStatusLabel(company.verification_status) }}</UiBadge>
              </div>

              <p class="mt-4 text-sm leading-6 text-slate-700">
                {{ company.description || 'Описание отсутствует.' }}
              </p>

              <div class="mt-4 grid gap-3 md:grid-cols-2">
                <div class="text-sm text-slate-600">
                  <span class="font-medium text-slate-900">Сайт:</span>
                  {{ company.website_url || '—' }}
                </div>
                <div class="text-sm text-slate-600">
                  <span class="font-medium text-slate-900">Город:</span>
                  {{ company.city || '—' }}
                </div>
                <div class="text-sm text-slate-600">
                  <span class="font-medium text-slate-900">ИНН:</span>
                  {{ company.inn || '—' }}
                </div>
                <div class="text-sm text-slate-600">
                  <span class="font-medium text-slate-900">Адрес:</span>
                  {{ company.address || '—' }}
                </div>
              </div>
            </div>

            <ModerationActionPanel
              :disabled="curatorStore.isSubmitting"
              @approve="onApprove(company.id, $event)"
              @needs-revision="onNeedsRevision(company.id, $event)"
              @reject="onReject(company.id, $event)"
            />
          </div>
        </UiCard>
      </div>
    </UiContainer>
  </section>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import type { CompanyVerificationStatus } from '@/entities/company/model/types';
import { useCuratorStore } from '@/features/curator/model/curator.store';
import ModerationActionPanel from '@/widgets/curator/ModerationActionPanel.vue';
import UiAlert from '@/shared/ui/UiAlert.vue';
import UiBadge from '@/shared/ui/UiBadge.vue';
import UiCard from '@/shared/ui/UiCard.vue';
import UiContainer from '@/shared/ui/UiContainer.vue';

const curatorStore = useCuratorStore();

onMounted(async () => {
  await Promise.all([
    curatorStore.loadCompanies(),
    curatorStore.loadAuditLogs(),
  ]);
});

async function onApprove(companyId: number, comment: string | null): Promise<void> {
  curatorStore.clearMessages();
  await curatorStore.moderateCompany(companyId, {
    action: 'approve',
    comment,
  });
}

async function onNeedsRevision(companyId: number, comment: string | null): Promise<void> {
  curatorStore.clearMessages();
  await curatorStore.moderateCompany(companyId, {
    action: 'needs_revision',
    comment,
  });
}

async function onReject(companyId: number, comment: string | null): Promise<void> {
  curatorStore.clearMessages();
  await curatorStore.moderateCompany(companyId, {
    action: 'reject',
    comment,
  });
}

function companyStatusLabel(status: CompanyVerificationStatus | null): string {
  switch (status) {
    case 'pending_verification':
      return 'На проверке';
    case 'needs_revision':
      return 'Нужны правки';
    case 'verified':
      return 'Подтверждена';
    case 'rejected':
      return 'Отклонена';
    default:
      return 'Черновик';
  }
}
</script>
