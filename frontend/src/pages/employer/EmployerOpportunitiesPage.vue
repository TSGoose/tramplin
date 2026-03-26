<template>
  <section class="page-section">
    <UiContainer>
      <div class="mb-8 flex items-start justify-between gap-4">
        <div>
          <h1 class="page-title">Мои возможности</h1>
          <p class="page-subtitle mt-3">
            Управляй опубликованными и черновыми возможностями компании.
          </p>
        </div>

        <RouterLink to="/employer/opportunities/new">
          <UiButton>Новая возможность</UiButton>
        </RouterLink>
      </div>

      <UiAlert v-if="employerStore.errorMessage" class="mb-5">
        {{ employerStore.errorMessage }}
      </UiAlert>

      <UiAlert v-if="employerStore.successMessage" variant="success" class="mb-5">
        {{ employerStore.successMessage }}
      </UiAlert>

      <div v-if="employerStore.isOpportunitiesLoading" class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        <UiCard v-for="index in 3" :key="index" className="p-6">
          <div class="space-y-3">
            <div class="h-4 w-24 animate-pulse rounded bg-slate-200" />
            <div class="h-6 w-3/4 animate-pulse rounded bg-slate-200" />
            <div class="h-4 w-full animate-pulse rounded bg-slate-200" />
          </div>
        </UiCard>
      </div>

      <div v-else-if="employerStore.opportunities.length === 0">
        <UiCard className="p-8 text-center">
          <h2 class="text-lg font-semibold text-slate-900">Пока нет возможностей</h2>
          <p class="mt-2 text-sm text-slate-600">
            Создай первую карточку вакансии, стажировки, менторства или события.
          </p>
        </UiCard>
      </div>

      <div v-else class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        <UiCard
          v-for="item in employerStore.opportunities"
          :key="item.id"
          className="p-6"
        >
          <div class="space-y-4">
            <div class="flex items-start justify-between gap-3">
              <div>
                <p class="text-xs uppercase tracking-wide text-slate-500">{{ typeLabel(item.type) }}</p>
                <h2 class="mt-1 text-lg font-semibold text-slate-900">{{ item.title }}</h2>
              </div>

              <UiBadge>{{ statusLabel(item.status) }}</UiBadge>
            </div>

            <p class="text-sm leading-6 text-slate-600">
              {{ item.short_description || 'Описание пока не заполнено.' }}
            </p>

            <div class="flex flex-wrap gap-2">
              <UiBadge v-if="item.city">{{ item.city }}</UiBadge>
              <UiBadge v-if="item.work_format" variant="warning">{{ workFormatLabel(item.work_format) }}</UiBadge>
            </div>
            <UiButton
              v-if="item.status === 'draft'"
              variant="secondary"
              :disabled="employerStore.isSubmitting"
              @click="onSubmit(item.id)"
            >
              Отправить на модерацию
            </UiButton>
          </div>
        </UiCard>
      </div>
    </UiContainer>
  </section>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import type { Opportunity } from '@/entities/opportunity/model/types';
import { useEmployerStore } from '@/features/employer/model/employer.store';
import UiAlert from '@/shared/ui/UiAlert.vue';
import UiBadge from '@/shared/ui/UiBadge.vue';
import UiButton from '@/shared/ui/UiButton.vue';
import UiCard from '@/shared/ui/UiCard.vue';
import UiContainer from '@/shared/ui/UiContainer.vue';
import { reactive } from 'vue';
import { ZodError } from 'zod';
import { createEmployerOpportunitySchema } from '@/features/employer/lib/employer.schemas';

const errors = reactive({
  title: '',
  full_description: '',
});

function resetErrors(): void {
  errors.title = '';
  errors.full_description = '';
}

function applyZodErrors(error: ZodError): void {
  for (const issue of error.issues) {
    const path = issue.path[0];
    if (typeof path === 'string' && path in errors) {
      // @ts-expect-error narrowed by runtime check
      errors[path] = issue.message;
    }
  }
}
const employerStore = useEmployerStore();

onMounted(async () => {
  await employerStore.loadOpportunities();
});


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

function statusLabel(status: Opportunity['status']): string {
  switch (status) {
    case 'pending_moderation':
      return 'На модерации';
    case 'published':
      return 'Опубликовано';
    case 'needs_revision':
      return 'Нужны правки';
    case 'rejected':
      return 'Отклонено';
    case 'archived':
      return 'Архив';
    case 'expired':
      return 'Истекло';
    default:
      return 'Черновик';
  }
}


async function onSubmit(id: number): Promise<void> {
  employerStore.clearMessages();
  await employerStore.sendOpportunityToModeration(id);
}
</script>
