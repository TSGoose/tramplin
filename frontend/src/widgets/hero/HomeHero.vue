<template>
  <section class="page-section">
    <UiContainer>
      <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
        <div>
          <UiBadge class="mb-4">Конкурсное демо</UiBadge>
          <h1 class="page-title max-w-3xl">
            Современная карьерная платформа для студентов, работодателей и карьерных центров вузов
          </h1>
          <p class="page-subtitle mt-4 max-w-2xl">
            Единая цифровая среда для поиска стажировок, вакансий, менторских программ и карьерных событий
            с удобным интерфейсом, ролью куратора и картой возможностей.
          </p>

          <div class="mt-8 flex flex-col gap-3 sm:flex-row">
            <RouterLink to="/register/applicant">
              <UiButton>Начать как студент</UiButton>
            </RouterLink>
            <RouterLink to="/register/employer">
              <UiButton variant="secondary">Подключить работодателя</UiButton>
            </RouterLink>
          </div>
        </div>

        <UiCard className="p-6">
          <div class="space-y-5">
            <div>
              <p class="text-sm font-medium text-slate-500">Возможности</p>
              <p class="mt-1 text-3xl font-semibold text-slate-900">20+</p>
            </div>

            <div class="grid gap-3 sm:grid-cols-2">
              <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-xs uppercase tracking-wide text-slate-500">Стажировки</p>
                <p class="mt-2 text-lg font-semibold text-slate-900">8</p>
              </div>
              <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-xs uppercase tracking-wide text-slate-500">Вакансии</p>
                <p class="mt-2 text-lg font-semibold text-slate-900">7</p>
              </div>
              <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-xs uppercase tracking-wide text-slate-500">Менторство</p>
                <p class="mt-2 text-lg font-semibold text-slate-900">3</p>
              </div>
              <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-xs uppercase tracking-wide text-slate-500">События</p>
                <p class="mt-2 text-lg font-semibold text-slate-900">5</p>
              </div>
            </div>

            <div class="rounded-2xl border border-blue-100 bg-blue-50 p-4">
              <p class="text-sm font-medium text-blue-900">Авторские механики</p>
              <p class="mt-2 text-sm leading-6 text-blue-800">
                Верификация работодателей, рекомендации между соискателями, модерация через куратора и карьерный
                профиль студента.
              </p>
            </div>
          </div>
        </UiCard>
      </div>
    </UiContainer>
  </section>
</template>

<script setup lang="ts">
import UiBadge from '@/shared/ui/UiBadge.vue';
import UiButton from '@/shared/ui/UiButton.vue';
import UiCard from '@/shared/ui/UiCard.vue';
import UiContainer from '@/shared/ui/UiContainer.vue';
</script>
