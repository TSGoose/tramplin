<template>
  <section class="page-section">
    <UiContainer className="max-w-xl">
      <UiCard className="p-8">
        <div class="mb-8">
          <h1 class="text-2xl font-semibold text-slate-900">Вход в платформу</h1>
          <p class="mt-2 text-sm leading-6 text-slate-600">
            Для студентов и работодателей. Подключение к API добавим на следующем шаге.
          </p>
        </div>

        <form class="space-y-5">
          <UiField label="Email">
            <UiInput v-model="email" type="email" placeholder="you@example.com" autocomplete="email" />
          </UiField>

          <UiField label="Пароль">
            <UiInput v-model="password" type="password" placeholder="Введите пароль" autocomplete="current-password" />
          </UiField>

          <UiButton type="submit" fullWidth>Войти</UiButton>
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
import { ref } from 'vue';
import UiButton from '@/shared/ui/UiButton.vue';
import UiCard from '@/shared/ui/UiCard.vue';
import UiContainer from '@/shared/ui/UiContainer.vue';
import UiField from '@/shared/ui/UiField.vue';
import UiInput from '@/shared/ui/UiInput.vue';

const email = ref('');
const password = ref('');
</script>
