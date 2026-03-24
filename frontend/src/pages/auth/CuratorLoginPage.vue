<template>
  <section class="page-section">
    <UiContainer className="max-w-xl">
      <UiCard className="p-8">
        <div class="mb-8">
          <h1 class="text-2xl font-semibold text-slate-900">Вход куратора</h1>
          <p class="mt-2 text-sm leading-6 text-slate-600">
            Отдельный контур входа для куратора и администратора платформы.
          </p>
        </div>

        <form class="space-y-5">
          <UiField label="Email">
            <UiInput v-model="email" type="email" placeholder="curator@tramplin.local" autocomplete="email" />
          </UiField>

          <UiField label="Пароль">
            <UiInput v-model="password" type="password" placeholder="Введите пароль" autocomplete="current-password" />
          </UiField>

          <UiButton type="submit" fullWidth>Войти как куратор</UiButton>
        </form>
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
