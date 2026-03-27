<template>
  <section class="relative overflow-hidden bg-slate-950 text-white">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(59,130,246,0.22),transparent_28%),radial-gradient(circle_at_bottom_right,rgba(14,165,233,0.16),transparent_26%)]" />

    <UiContainer className="relative py-20 lg:py-28">
      <div class="grid items-center gap-10 lg:grid-cols-[1.15fr_0.85fr]">
        <div>
          <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-medium uppercase tracking-[0.2em] text-sky-200">
            Цифровая карьерная экосистема ВУЗа
          </div>

          <h1 class="mt-6 max-w-4xl text-4xl font-semibold leading-tight tracking-tight text-white md:text-5xl lg:text-6xl">
            От профиля студента до модерации работодателей — в одной платформе
          </h1>

          <p class="mt-6 max-w-2xl text-base leading-8 text-slate-300 md:text-lg">
            Tramplin объединяет студентов, работодателей, кураторов и администраторов в единой цифровой среде.
            Платформа помогает находить карьерные возможности, управлять откликами, верифицировать компании
            и поддерживать качество контента через прозрачную модерацию.
          </p>

          <div class="mt-8 flex flex-wrap gap-3">
            <RouterLink to="/opportunities">
              <UiButton>Открыть каталог</UiButton>
            </RouterLink>

            <RouterLink to="/register/applicant">
              <UiButton variant="secondary">Я студент</UiButton>
            </RouterLink>

            <RouterLink to="/register/employer">
              <UiButton variant="ghost">Я работодатель</UiButton>
            </RouterLink>
          </div>

          <div class="mt-10 grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-sm">
              <p class="text-2xl font-semibold text-white">4 роли</p>
              <p class="mt-1 text-sm text-slate-300">Студент, работодатель, куратор, администратор</p>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-sm">
              <p class="text-2xl font-semibold text-white">End-to-end</p>
              <p class="mt-1 text-sm text-slate-300">От публикации до отклика и проверки</p>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-sm">
              <p class="text-2xl font-semibold text-white">Модерация</p>
              <p class="mt-1 text-sm text-slate-300">Контроль качества компаний и возможностей</p>
            </div>
          </div>
        </div>

        <div class="relative">
          <div class="rounded-[28px] border border-white/10 bg-white/5 p-5 shadow-2xl backdrop-blur-md">
            <div class="rounded-[24px] border border-white/10 bg-slate-900/80 p-6">
              <div class="flex items-center justify-between gap-4">
                <div>
                  <p class="text-xs uppercase tracking-[0.18em] text-slate-400">Demo flow</p>
                  <h2 class="mt-2 text-2xl font-semibold text-white">Как выглядит продукт</h2>
                </div>

                <div class="rounded-full border border-emerald-400/25 bg-emerald-400/10 px-3 py-1 text-xs font-medium text-emerald-300">
                  Рабочий сценарий
                </div>
              </div>

              <div class="mt-6 space-y-4">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                  <p class="text-sm font-medium text-white">1. Студент</p>
                  <p class="mt-2 text-sm leading-6 text-slate-300">
                    Создает профиль, фильтрует каталог, сохраняет возможности и отправляет отклики.
                  </p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                  <p class="text-sm font-medium text-white">2. Работодатель</p>
                  <p class="mt-2 text-sm leading-6 text-slate-300">
                    Заполняет компанию, проходит верификацию, создает карточки вакансий и стажировок.
                  </p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                  <p class="text-sm font-medium text-white">3. Куратор</p>
                  <p class="mt-2 text-sm leading-6 text-slate-300">
                    Проверяет компании и возможности, оставляет комментарии и формирует журнал решений.
                  </p>
                </div>
              </div>

              <div class="mt-6 rounded-2xl border border-sky-400/20 bg-sky-500/10 p-4">
                <p class="text-sm font-medium text-sky-200">Конкурсный акцент</p>
                <p class="mt-2 text-sm leading-6 text-slate-200">
                  Платформа демонстрирует не отдельный кабинет, а полноценную цифровую систему взаимодействия ВУЗа,
                  студентов и работодателей.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </UiContainer>
  </section>
</template>

<script setup lang="ts">
import UiButton from '@/shared/ui/UiButton.vue';
import UiContainer from '@/shared/ui/UiContainer.vue';
</script>
