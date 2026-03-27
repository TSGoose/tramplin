<template>
  <header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/90 backdrop-blur">
    <UiContainer className="flex h-16 items-center justify-between">
      <RouterLink to="/" class="flex items-center gap-3">
        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-sm font-bold text-white">
          Т
        </div>
        <div class="flex flex-col">
          <span class="text-sm font-semibold text-slate-900">Трамплин</span>
          <span class="text-xs text-slate-500">Карьерная платформа для вузов</span>
        </div>
      </RouterLink>

      <nav class="hidden items-center gap-6 md:flex">
        <RouterLink class="text-sm text-slate-600 transition hover:text-slate-900" to="/">
          Главная
        </RouterLink>
        <RouterLink class="text-sm text-slate-600 transition hover:text-slate-900" to="/opportunities">
          Возможности
        </RouterLink>
        <RouterLink class="text-sm text-slate-600 transition hover:text-slate-900" to="/login">
          Вход
        </RouterLink>
        <RouterLink class="text-sm text-slate-600 transition hover:text-slate-900" to="/register/applicant">
          Студенту
        </RouterLink>
        <RouterLink class="text-sm text-slate-600 transition hover:text-slate-900" to="/register/employer">
          Работодателю
        </RouterLink>
      </nav>

      <div v-if="authStore.isAuthenticated && authStore.user" class="flex items-center gap-3">
        <RouterLink
          v-if="authStore.user.role === 'admin'"
          to="/admin/users"
          class="hidden text-sm font-medium text-slate-700 transition hover:text-slate-900 sm:block"
        >
          Пользователи
        </RouterLink>

      
        <RouterLink
          v-if="authStore.user.role === 'curator' || authStore.user.role === 'admin'"
          to="/curator/companies"
          class="hidden text-sm font-medium text-slate-700 transition hover:text-slate-900 sm:block"
        >
          Компании
        </RouterLink>

        <RouterLink
          v-if="authStore.user.role === 'curator' || authStore.user.role === 'admin'"
          to="/curator/opportunities"
          class="hidden text-sm font-medium text-slate-700 transition hover:text-slate-900 sm:block"
        >
          Модерация
        </RouterLink>

        <RouterLink
          v-if="authStore.user.role === 'curator' || authStore.user.role === 'admin'"
          to="/curator/audit-logs"
          class="hidden text-sm font-medium text-slate-700 transition hover:text-slate-900 sm:block"
        >
          Журнал
        </RouterLink>
       
        <RouterLink
          v-if="authStore.user.role === 'employer'"
          to="/employer/company"
          class="hidden text-sm font-medium text-slate-700 transition hover:text-slate-900 sm:block"
        >
          Компания
        </RouterLink>

        <RouterLink
          v-if="authStore.user.role === 'employer'"
          to="/employer/opportunities"
          class="hidden text-sm font-medium text-slate-700 transition hover:text-slate-900 sm:block"
        >
          Мои возможности
        </RouterLink>

        <RouterLink
          v-if="authStore.user.role === 'applicant'"
          to="/applicant/profile"
          class="hidden text-sm font-medium text-slate-700 transition hover:text-slate-900 sm:block"
        >
          Профиль
        </RouterLink>

        <RouterLink
          v-if="authStore.user.role === 'applicant'"
          to="/applicant/favorites"
          class="hidden text-sm font-medium text-slate-700 transition hover:text-slate-900 sm:block"
        >
          Избранное
        </RouterLink>

        <RouterLink
          v-if="authStore.user.role === 'applicant'"
          to="/applicant/applications"
          class="hidden text-sm font-medium text-slate-700 transition hover:text-slate-900 sm:block"
        >
          Отклики
        </RouterLink>

        <div class="hidden text-right sm:block">
          <p class="text-sm font-medium text-slate-900">{{ authStore.user.display_name }}</p>
          <p class="text-xs uppercase tracking-wide text-slate-500">{{ authStore.user.role }}</p>
        </div>
        <UiButton variant="secondary" @click="onLogout">Выйти</UiButton>
      </div>

      <div v-else class="flex items-center gap-2">
        <RouterLink to="/login/curator">
          <UiButton variant="ghost">Куратор</UiButton>
        </RouterLink>
        <RouterLink to="/login">
          <UiButton>Войти</UiButton>
        </RouterLink>
      </div>
    </UiContainer>
  </header>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';
import UiContainer from '@/shared/ui/UiContainer.vue';
import UiButton from '@/shared/ui/UiButton.vue';
import { useAuthStore } from '@/features/auth/model/auth.store';

const router = useRouter();
const authStore = useAuthStore();

async function onLogout(): Promise<void> {
  await authStore.logout();
  await router.push('/');
}
</script>
