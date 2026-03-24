<template>
  <section class="page-section">
    <UiContainer className="max-w-xl">
      <UiCard className="p-8">
        <div class="mb-8">
          <h1 class="text-2xl font-semibold text-slate-900">Вход в платформу</h1>
          <p class="mt-2 text-sm leading-6 text-slate-600">
            Для студентов и работодателей.
          </p>
        </div>

        <UiAlert v-if="authStore.errorMessage" class="mb-5">
          {{ authStore.errorMessage }}
        </UiAlert>

        <form class="space-y-5" @submit.prevent="onSubmit">
          <UiField label="Email" :error="errors.email">
            <UiInput v-model="form.email" type="email" placeholder="you@example.com" autocomplete="email" />
          </UiField>

          <UiField label="Пароль" :error="errors.password">
            <UiInput v-model="form.password" type="password" placeholder="Введите пароль" autocomplete="current-password" />
          </UiField>

          <UiButton type="submit" :disabled="authStore.isLoading" fullWidth>
            {{ authStore.isLoading ? 'Вход...' : 'Войти' }}
          </UiButton>
        </form>

        <div class="mt-6 flex flex-col gap-2 text-sm text-slate-600">
          <RouterLink class="text-blue-600 hover:text-blue-700" to="/register/applicant">
            Нет аккаунта студента
          </RouterLink>
          <RouterLink class="text-blue-600 hover:text-blue-700" to="/register/employer">
            Нет аккаунта работодателя
          </RouterLink>
        </div>
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
import { loginSchema } from '@/features/auth/lib/auth.schemas';
import { useAuthStore } from '@/features/auth/model/auth.store';

const router = useRouter();
const authStore = useAuthStore();

const form = reactive({
  email: '',
  password: '',
});

const errors = reactive<Record<string, string>>({
  email: '',
  password: '',
});

async function onSubmit(): Promise<void> {
  resetErrors();

  try {
    loginSchema.parse(form);
    await authStore.login({
      email: form.email,
      password: form.password,
    });

    await router.push('/');
  } catch (error: unknown) {
    if (error instanceof ZodError) {
      applyZodErrors(error);
    }
  }
}

function resetErrors(): void {
  errors.email = '';
  errors.password = '';
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
