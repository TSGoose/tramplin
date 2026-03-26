<template>
  <div>
    <HomeHero />

    <section class="page-section pt-0">
      <UiContainer>
        <div class="mb-6">
          <RouterLink to="/opportunities">
            <UiButton variant="secondary">Открыть каталог возможностей</UiButton>
          </RouterLink>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
          <UiCard className="p-6">
            <h2 class="text-lg font-semibold text-slate-900">Для студентов</h2>
            <p class="mt-3 text-sm leading-6 text-slate-600">
              Профиль, отклики, избранное, карьерные рекомендации и доступ к возможностям от верифицированных работодателей.
            </p>
          </UiCard>

          <UiCard className="p-6">
            <h2 class="text-lg font-semibold text-slate-900">Для работодателей</h2>
            <p class="mt-3 text-sm leading-6 text-slate-600">
              Создание карточек возможностей, управление откликами, базовая аналитика и прозрачный процесс верификации.
            </p>
          </UiCard>

          <UiCard className="p-6">
            <h2 class="text-lg font-semibold text-slate-900">Для вузов</h2>
            <p class="mt-3 text-sm leading-6 text-slate-600">
              Единая карьерная среда с ролью куратора, модерацией и цифровой связкой между студентами и рынком труда.
            </p>
          </UiCard>
        </div>
      </UiContainer>
    </section>
  </div>
</template>

<script setup lang="ts">
import HomeHero from '@/widgets/hero/HomeHero.vue';
import UiButton from '@/shared/ui/UiButton.vue';
import UiCard from '@/shared/ui/UiCard.vue';
import UiContainer from '@/shared/ui/UiContainer.vue';
</script>
