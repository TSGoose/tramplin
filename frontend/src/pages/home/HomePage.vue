<template>
  <div>
    <HomeHero />

    <section class="page-section">
      <UiContainer>
        <div class="mb-8 max-w-3xl">
          <p class="text-sm font-medium uppercase tracking-[0.18em] text-blue-600">Для кого платформа</p>
          <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900">
            Единая цифровая среда для карьерного трека внутри ВУЗа
          </h2>
          <p class="mt-4 text-base leading-8 text-slate-600">
            Tramplin строится как продукт, который помогает не только размещать вакансии,
            но и управлять качеством, прозрачностью и удобством взаимодействия всех участников процесса.
          </p>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
          <UiCard className="p-6">
            <div class="flex h-full flex-col">
              <p class="text-sm font-medium uppercase tracking-[0.18em] text-blue-600">Студент</p>
              <h3 class="mt-3 text-xl font-semibold text-slate-900">Цифровой карьерный профиль</h3>
              <p class="mt-4 text-sm leading-7 text-slate-600">
                Студент заполняет профиль, выбирает формат работы, сохраняет интересные возможности,
                откликается и отслеживает историю взаимодействия.
              </p>
              <div class="mt-6">
                <RouterLink to="/register/applicant">
                  <UiButton variant="secondary">Перейти в студенческий контур</UiButton>
                </RouterLink>
              </div>
            </div>
          </UiCard>

          <UiCard className="p-6">
            <div class="flex h-full flex-col">
              <p class="text-sm font-medium uppercase tracking-[0.18em] text-blue-600">Работодатель</p>
              <h3 class="mt-3 text-xl font-semibold text-slate-900">Верифицированный контур публикации</h3>
              <p class="mt-4 text-sm leading-7 text-slate-600">
                Работодатель оформляет компанию, проходит проверку, публикует вакансии и стажировки,
                получает замечания и повторно отправляет карточки после исправлений.
              </p>
              <div class="mt-6">
                <RouterLink to="/register/employer">
                  <UiButton variant="secondary">Перейти в контур работодателя</UiButton>
                </RouterLink>
              </div>
            </div>
          </UiCard>

          <UiCard className="p-6">
            <div class="flex h-full flex-col">
              <p class="text-sm font-medium uppercase tracking-[0.18em] text-blue-600">Куратор / ВУЗ</p>
              <h3 class="mt-3 text-xl font-semibold text-slate-900">Прозрачная модерация и контроль качества</h3>
              <p class="mt-4 text-sm leading-7 text-slate-600">
                Куратор проверяет компании и возможности, управляет качеством контента,
                ведет историю решений и поддерживает доверие к платформе.
              </p>
              <div class="mt-6">
                <RouterLink to="/login/curator">
                  <UiButton variant="secondary">Перейти в контур куратора</UiButton>
                </RouterLink>
              </div>
            </div>
          </UiCard>
        </div>
      </UiContainer>
    </section>

    <section class="page-section bg-slate-50">
      <UiContainer>
        <div class="mb-8 max-w-3xl">
          <p class="text-sm font-medium uppercase tracking-[0.18em] text-blue-600">Как это работает</p>
          <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900">
            Платформа закрывает полный цикл карьерного взаимодействия
          </h2>
        </div>

        <div class="grid gap-6 lg:grid-cols-4">
          <UiCard className="p-6">
            <p class="text-3xl font-semibold text-blue-600">01</p>
            <h3 class="mt-4 text-lg font-semibold text-slate-900">Создание профиля</h3>
            <p class="mt-3 text-sm leading-7 text-slate-600">
              Студент формирует карьерный профиль и настраивает предпочтения по возможностям.
            </p>
          </UiCard>

          <UiCard className="p-6">
            <p class="text-3xl font-semibold text-blue-600">02</p>
            <h3 class="mt-4 text-lg font-semibold text-slate-900">Публикация работодателя</h3>
            <p class="mt-3 text-sm leading-7 text-slate-600">
              Компания проходит верификацию и размещает карьерные предложения в системе.
            </p>
          </UiCard>

          <UiCard className="p-6">
            <p class="text-3xl font-semibold text-blue-600">03</p>
            <h3 class="mt-4 text-lg font-semibold text-slate-900">Модерация куратора</h3>
            <p class="mt-3 text-sm leading-7 text-slate-600">
              Куратор проверяет качество и релевантность карточек перед публикацией.
            </p>
          </UiCard>

          <UiCard className="p-6">
            <p class="text-3xl font-semibold text-blue-600">04</p>
            <h3 class="mt-4 text-lg font-semibold text-slate-900">Отклик и сопровождение</h3>
            <p class="mt-3 text-sm leading-7 text-slate-600">
              Студент находит возможность, откликается и отслеживает дальнейший путь.
            </p>
          </UiCard>
        </div>
      </UiContainer>
    </section>

    <section class="page-section">
      <UiContainer>
        <div class="mb-8 max-w-3xl">
          <p class="text-sm font-medium uppercase tracking-[0.18em] text-blue-600">Почему это сильное решение</p>
          <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900">
            Не просто сайт с вакансиями, а цифровая карьерная экосистема
          </h2>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
          <UiCard className="p-6">
            <h3 class="text-lg font-semibold text-slate-900">Верификация компаний</h3>
            <p class="mt-3 text-sm leading-7 text-slate-600">
              Повышает доверие студентов и помогает поддерживать качество работодателей внутри платформы.
            </p>
          </UiCard>

          <UiCard className="p-6">
            <h3 class="text-lg font-semibold text-slate-900">Каталог + карта</h3>
            <p class="mt-3 text-sm leading-7 text-slate-600">
              Возможности можно изучать как списком, так и на карте, что усиливает цифровой UX.
            </p>
          </UiCard>

          <UiCard className="p-6">
            <h3 class="text-lg font-semibold text-slate-900">Реальный revision flow</h3>
            <p class="mt-3 text-sm leading-7 text-slate-600">
              Работодатели не просто публикуют карточки, а получают замечания и возвращаются к качественной доработке.
            </p>
          </UiCard>

          <UiCard className="p-6">
            <h3 class="text-lg font-semibold text-slate-900">Журнал решений</h3>
            <p class="mt-3 text-sm leading-7 text-slate-600">
              Модерация фиксируется в audit log, что делает систему управляемой и прозрачной.
            </p>
          </UiCard>
        </div>
      </UiContainer>
    </section>

    <section class="page-section bg-slate-950 text-white">
      <UiContainer>
        <div class="rounded-[28px] border border-white/10 bg-white/5 p-8 md:p-10">
          <div class="grid items-center gap-8 lg:grid-cols-[1fr_auto]">
            <div>
              <p class="text-sm font-medium uppercase tracking-[0.18em] text-sky-200">Быстрый старт</p>
              <h2 class="mt-3 text-3xl font-semibold tracking-tight text-white">
                Платформа уже готова к демонстрации основных сценариев
              </h2>
              <p class="mt-4 max-w-3xl text-base leading-8 text-slate-300">
                Можно показать путь студента, работодателя, куратора и администратора в рамках одного продукта:
                от публикации до отклика и модерации.
              </p>
            </div>

            <div class="flex flex-wrap gap-3">
              <RouterLink to="/opportunities">
                <UiButton>Открыть каталог</UiButton>
              </RouterLink>

              <RouterLink to="/login">
                <UiButton variant="secondary">Войти в систему</UiButton>
              </RouterLink>
            </div>
          </div>
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
