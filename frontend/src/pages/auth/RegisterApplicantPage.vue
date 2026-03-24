<template>
  <section class="page-section">
    <UiContainer className="max-w-2xl">
      <UiCard className="p-8">
        <div class="mb-8">
          <h1 class="text-2xl font-semibold text-slate-900">Регистрация студента</h1>
          <p class="mt-2 text-sm leading-6 text-slate-600">
            Создание аккаунта соискателя.
          </p>
        </div>

        <UiAlert v-if="authStore.errorMessage" class="mb-5">
          {{ authStore.errorMessage }}
        </UiAlert>

        <form class="grid gap-5 md:grid-cols-2" @submit.prevent="onSubmit">
          <UiField label="Имя и фамилия" :error="errors.display_name">
            <UiInput v-model="form.display_name" placeholder="Иван Петров" autocomplete="name" />
          </UiField>

          <UiField label="Email" :error="errors.email">
            <UiInput v-model="form.email" type="email" placeholder="student@example.com" autocomplete="email" />
          </UiField>

          <UiField label="Пароль" :error="errors.password">
            <UiInput v-model="form.password" type="password" placeholder="Минимум 8 символов" autocomplete="new-password" />
          </UiField>

          <UiField label="Подтверждение пароля" :error="errors.password_confirmation">
            <UiInput
              v-model="form.password_confirmation"
              type="password"
              placeholder="Повторите пароль"
              autocomplete="new-password"
            />
          </UiField>

          <div class="md:col-span-2">
            <UiButton type="submit" :disabled="authStore.isLoading" fullWidth>
              {{ authStore.isLoading ? 'Создание...' : 'Создать аккаунт студента' }}
            </UiButton>
          </div>
        </form>
      </UiCard>
    </UiContainer>
  </section>
</template>

<script setup lang="ts">
import { reactive } from 'vue';
import { useRouter } from 'vue-router';
import { ZodError } from 'zod';
import UiAlert from '@/shared/ui/UiAlert.vue';
import UiButton from '@/shared/ui/UiButton.vue';
import UiCard from '@/shared/ui/UiCard.vue';
import UiContainer from '@/shared/ui/UiContainer.vue';
import UiField from '@/shared/ui/UiField.vue';
import UiInput from '@/shared/ui/UiInput.vue';
import { registerSchema } from '@/features/auth/lib/auth.schemas';
import { useAuthStore } from '@/features/auth/model/auth.store';

const router = useRouter();
const authStore = useAuthStore();

const form = reactive({
  display_name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const errors = reactive<Record<string, string>>({
  display_name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

async function onSubmit(): Promise<void> {
  resetErrors();

  try {
    registerSchema.parse(form);

    await authStore.registerApplicant({
      display_name: form.display_name,
      email: form.email,
      password: form.password,
      password_confirmation: form.password_confirmation,
    });

    await router.push('/');
  } catch (error: unknown) {
    if (error instanceof ZodError) {
      applyZodErrors(error);
    }
  }
}

function resetErrors(): void {
  errors.display_name = '';
  errors.email = '';
  errors.password = '';
  errors.password_confirmation = '';
}

function applyZodErrors(error: ZodError): void {
  for (const issue of error.issues) {
    const path = issue.path[0];
    if (typeof path === 'string') {
      errors[path] = issue.message;
    }
  }
}
</script>
