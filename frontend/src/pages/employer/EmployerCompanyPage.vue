<template>
  <section class="page-section">
    <UiContainer className="max-w-4xl">
      <div class="mb-8">
        <h1 class="page-title">Профиль компании</h1>
        <p class="page-subtitle mt-3">
          Заполни данные компании и отправь профиль на верификацию.
        </p>
      </div>

      <UiAlert v-if="employerStore.errorMessage" class="mb-5">
        {{ employerStore.errorMessage }}
      </UiAlert>

      <UiAlert v-if="employerStore.successMessage" variant="success" class="mb-5">
        {{ employerStore.successMessage }}
      </UiAlert>

      <UiCard v-if="employerStore.isCompanyLoading" className="p-8">
        <p class="text-sm text-slate-600">Загрузка профиля компании...</p>
      </UiCard>

      <UiCard v-else className="p-8">
        <div class="mb-6 flex items-center justify-between gap-4">
          <div>
            <p class="text-sm text-slate-500">Статус верификации</p>
            <p class="mt-1 text-lg font-semibold text-slate-900">{{ verificationLabel }}</p>
          </div>

          <UiCard
            v-if="employerStore.company?.verification_comment"
            className="mb-6 border border-amber-200 bg-amber-50 p-6"
          >
            <h2 class="text-lg font-semibold text-amber-900">Замечание куратора</h2>
            <p class="mt-2 text-sm leading-6 text-amber-800">
              {{ employerStore.company.verification_comment }}
            </p>
          </UiCard>

        <UiButton
            variant="secondary"
            :disabled="employerStore.isSubmitting"
            @click="onSubmitForVerification"
          >
            {{
              employerStore.company?.verification_status === 'needs_revision'
                ? 'Отправить повторно'
                : 'Отправить на верификацию'
            }}
          </UiButton>
        </div>

        <form class="grid gap-5 md:grid-cols-2" @submit.prevent="onSave">
          <UiField label="Название компании">
            <UiInput v-model="form.name" placeholder="Digital Horizon" />
          </UiField>

          <UiField label="Отрасль">
            <UiInput v-model="form.industry" placeholder="IT / EdTech / Fintech" />
          </UiField>

          <UiField label="Сайт">
            <UiInput v-model="form.website_url" placeholder="https://company.example" />
          </UiField>

          <UiField label="Социальная ссылка">
            <UiInput v-model="form.social_url" placeholder="https://t.me/company" />
          </UiField>

          <UiField label="ИНН">
            <UiInput v-model="form.inn" placeholder="7701234567" />
          </UiField>

          <UiField label="Город">
            <UiInput v-model="form.city" placeholder="Москва" />
          </UiField>

          <div class="md:col-span-2">
            <UiField label="Адрес">
              <UiInput v-model="form.address" placeholder="ул. Тверская, 1" />
            </UiField>
          </div>

          <div class="md:col-span-2">
            <UiField label="Описание">
              <textarea
                v-model="form.description"
                rows="5"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                placeholder="Коротко расскажите о компании и направлениях работы"
              />
            </UiField>
          </div>

          <div class="md:col-span-2 flex justify-end">
            <UiButton type="submit" :disabled="employerStore.isSubmitting">
              {{ employerStore.isSubmitting ? 'Сохранение...' : 'Сохранить компанию' }}
            </UiButton>
          </div>
        </form>
      </UiCard>
    </UiContainer>
  </section>
</template>

<script setup lang="ts">
import { computed, onMounted, reactive, watch } from 'vue';
import UiAlert from '@/shared/ui/UiAlert.vue';
import UiButton from '@/shared/ui/UiButton.vue';
import UiCard from '@/shared/ui/UiCard.vue';
import UiContainer from '@/shared/ui/UiContainer.vue';
import UiField from '@/shared/ui/UiField.vue';
import UiInput from '@/shared/ui/UiInput.vue';
import { useEmployerStore } from '@/features/employer/model/employer.store';

const employerStore = useEmployerStore();

const form = reactive({
  name: '',
  description: '',
  industry: '',
  website_url: '',
  social_url: '',
  inn: '',
  city: '',
  address: '',
});

const verificationLabel = computed(() => {
  switch (employerStore.company?.verification_status) {
    case 'verified':
      return 'Компания подтверждена';
    case 'pending_verification':
      return 'На проверке';
    case 'needs_revision':
      return 'Нужны правки';
    case 'rejected':
      return 'Отклонена';
    default:
      return 'Черновик';
  }
});

onMounted(async () => {
  await employerStore.loadCompany();
  syncForm();
});

watch(
  () => employerStore.company,
  () => {
    syncForm();
  },
);

function syncForm(): void {
  const company = employerStore.company;
  if (!company) {
    return;
  }

  form.name = company.name ?? '';
  form.description = company.description ?? '';
  form.industry = company.industry ?? '';
  form.website_url = company.website_url ?? '';
  form.social_url = company.social_url ?? '';
  form.inn = company.inn ?? '';
  form.city = company.city ?? '';
  form.address = company.address ?? '';
}

async function onSave(): Promise<void> {
  employerStore.clearMessages();

  await employerStore.saveCompany({
    name: form.name.trim(),
    description: nullable(form.description),
    industry: nullable(form.industry),
    website_url: nullable(form.website_url),
    social_url: nullable(form.social_url),
    inn: nullable(form.inn),
    city: nullable(form.city),
    address: nullable(form.address),
  });
}

async function onSubmitForVerification(): Promise<void> {
  employerStore.clearMessages();
  await employerStore.sendCompanyToVerification();
}

function nullable(value: string): string | null {
  const normalized = value.trim();
  return normalized.length > 0 ? normalized : null;
}
</script>
