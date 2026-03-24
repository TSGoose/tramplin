<script setup lang="ts">
interface Props {
  title: string;
  description: string;
  submitLabel: string;
}

defineProps<Props>();
</script>

<template>
  <div class="w-full max-w-md rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
    <div class="mb-6 space-y-2">
      <h1 class="text-2xl font-semibold text-slate-950">{{ title }}</h1>
      <p class="text-sm leading-6 text-slate-500">{{ description }}</p>
    </div>

    <form class="space-y-4">
      <label class="block space-y-2">
        <span class="text-sm font-medium text-slate-700">Email</span>
        <input class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-brand-500" type="email" placeholder="name@example.com" />
      </label>

      <label class="block space-y-2">
        <span class="text-sm font-medium text-slate-700">Пароль</span>
        <input class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-brand-500" type="password" placeholder="••••••••" />
      </label>

      <button type="button" class="w-full rounded-xl bg-slate-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-slate-800">
        {{ submitLabel }}
      </button>
    </form>
  </div>
</template>
