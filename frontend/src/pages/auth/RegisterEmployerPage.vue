<template>
  <section class="page-section">
    <UiContainer className="max-w-2xl">
      <UiCard className="p-8">
        <div class="mb-8">
          <h1 class="text-2xl font-semibold text-slate-900">Регистрация работодателя</h1>
          <p class="mt-2 text-sm leading-6 text-slate-600">
            После подключения backend добавим поток верификации компании и отдельную работу с профилем организации.
          </p>
        </div>

        <form class="grid gap-5 md:grid-cols-2">
          <UiField label="Имя представителя">
            <UiInput v-model="displayName" placeholder="Анна Смирнова" autocomplete="name" />
          </UiField>

          <UiField label="Email">
            <UiInput v-model="email" type="email" placeholder="hr@company.ru" autocomplete="email" />
          </UiField>

          <UiField label="Пароль">
            <UiInput v-model="password" type="password" placeholder="Минимум 8 символов" autocomplete="new-password" />
          </UiField>

          <UiField label="Подтверждение пароля">
            <UiInput
              v-model="passwordConfirmation"
              type="password"
              placeholder="Повторите пароль"
              autocomplete="new-password"
            />
          </UiField>

          <div class="md:col-span-2">
            <UiButton type="submit" fullWidth>Создать аккаунт работодателя</UiButton>
          </div>
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

const displayName = ref('');
const email = ref('');
const password = ref('');
const passwordConfirmation = ref('');
</script>
