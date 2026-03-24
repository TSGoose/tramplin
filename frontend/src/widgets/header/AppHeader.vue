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

      <div class="flex items-center gap-2">
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
import UiContainer from '@/shared/ui/UiContainer.vue';
import UiButton from '@/shared/ui/UiButton.vue';
</script>
