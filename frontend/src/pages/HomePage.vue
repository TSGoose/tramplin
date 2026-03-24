<template>
  <section class="mx-auto grid max-w-7xl gap-8 px-6 py-16 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
    <div class="space-y-6">
      <span class="inline-flex rounded-full border border-brand-100 bg-brand-50 px-3 py-1 text-sm font-medium text-brand-700">
        Конкурсный каркас
      </span>
      <div class="space-y-4">
        <h1 class="max-w-2xl text-4xl font-semibold tracking-tight text-slate-950 lg:text-5xl">
          Единая карьерная платформа для студентов, работодателей и карьерных центров.
        </h1>
        <p class="max-w-2xl text-lg leading-8 text-slate-600">
          Каркас готов под поэтапную разработку: публичный каталог возможностей, профили соискателей,
          кабинеты работодателей, модерацию кураторов и авторские механики матчей.
        </p>
      </div>
      <div class="flex flex-wrap gap-3">
        <RouterLink to="/login" class="rounded-xl bg-slate-950 px-5 py-3 text-sm font-medium text-white transition hover:bg-slate-800">
          Начать с auth-блока
        </RouterLink>
        <RouterLink to="/login/curator" class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-white">
          Проверить вход куратора
        </RouterLink>
      </div>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
      <div class="space-y-4">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-semibold text-slate-950">Roadmap</h2>
          <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">Stage 0</span>
        </div>

        <ul class="space-y-3 text-sm text-slate-600">
          <li>• Docker + backend/frontend structure</li>
          <li>• Role enum + users migration + seed base</li>
          <li>• Vue shell + router + auth screens</li>
          <li>• Test-first flow для следующих функциональных блоков</li>
        </ul>
      </div>
    </div>
  </section>
</template>
