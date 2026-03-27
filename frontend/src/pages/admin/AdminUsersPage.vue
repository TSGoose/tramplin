
<template>
  <section class="page-section">
    <UiContainer>
      <div class="mb-8">
        <h1 class="page-title">Пользователи</h1>
        <p class="page-subtitle mt-3">
          Управление ролями и создание новых кураторов.
        </p>
      </div>

      <UiAlert v-if="adminStore.errorMessage" class="mb-5">
        {{ adminStore.errorMessage }}
      </UiAlert>

      <UiAlert v-if="adminStore.successMessage" variant="success" class="mb-5">
        {{ adminStore.successMessage }}
      </UiAlert>

      <div class="grid gap-6 xl:grid-cols-[0.8fr_1.2fr]">
        <UiCard className="p-6">
          <div class="space-y-4">
            <h2 class="text-lg font-semibold text-slate-900">Создать куратора</h2>

            <UiField label="Имя">
              <UiInput v-model="form.display_name" placeholder="Иван Иванов" />
            </UiField>

            <UiField label="Email">
              <UiInput v-model="form.email" type="email" placeholder="curator@example.com" />
            </UiField>

            <UiField label="Пароль">
              <UiInput v-model="form.password" type="password" placeholder="Минимум 8 символов" />
            </UiField>

            <UiField label="Подтверждение пароля">
              <UiInput v-model="form.password_confirmation" type="password" placeholder="Повтори пароль" />
            </UiField>

            <UiButton :disabled="adminStore.isSubmitting" fullWidth @click="onCreateCurator">
              {{ adminStore.isSubmitting ? 'Создание...' : 'Создать куратора' }}
            </UiButton>
          </div>
        </UiCard>

        <UiCard className="p-6">
          <div class="mb-4 flex items-center justify-between gap-4">
            <h2 class="text-lg font-semibold text-slate-900">Список пользователей</h2>

            <select
              v-model="adminStore.selectedRole"
              class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
              @change="onReload"
            >
              <option value="">Все роли</option>
              <option value="admin">Admin</option>
              <option value="curator">Curator</option>
              <option value="employer">Employer</option>
              <option value="applicant">Applicant</option>
            </select>
          </div>

          <div v-if="adminStore.isLoading" class="space-y-3">
            <div v-for="index in 5" :key="index" class="h-14 animate-pulse rounded-xl bg-slate-100" />
          </div>

          <div v-else-if="adminStore.users.length === 0" class="rounded-xl border border-slate-200 p-6 text-center">
            <p class="text-sm text-slate-600">Пользователи не найдены.</p>
          </div>

          <div v-else class="space-y-3">
            <div
              v-for="user in adminStore.users"
              :key="user.id"
              class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 p-4"
            >
              <div>
                <p class="font-medium text-slate-900">{{ user.display_name }}</p>
                <p class="mt-1 text-sm text-slate-600">{{ user.email }}</p>
              </div>

              <UiBadge>{{ user.role }}</UiBadge>
            </div>
          </div>
        </UiCard>
      </div>
    </UiContainer>
  </section>
</template>

<script setup lang="ts">
import { onMounted, reactive } from 'vue';
import { useAdminStore } from '@/features/admin/model/admin.store';
import UiAlert from '@/shared/ui/UiAlert.vue';
import UiBadge from '@/shared/ui/UiBadge.vue';
import UiButton from '@/shared/ui/UiButton.vue';
import UiCard from '@/shared/ui/UiCard.vue';
import UiContainer from '@/shared/ui/UiContainer.vue';
import UiField from '@/shared/ui/UiField.vue';
import UiInput from '@/shared/ui/UiInput.vue';

const adminStore = useAdminStore();

const form = reactive({
  display_name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

onMounted(async () => {
  await adminStore.loadUsers();
});

async function onReload(): Promise<void> {
  await adminStore.loadUsers();
}

async function onCreateCurator(): Promise<void> {
  adminStore.clearMessages();

  await adminStore.createCuratorUser({
    display_name: form.display_name.trim(),
    email: form.email.trim(),
    password: form.password,
    password_confirmation: form.password_confirmation,
  });

  form.display_name = '';
  form.email = '';
  form.password = '';
  form.password_confirmation = '';
}
</script>
