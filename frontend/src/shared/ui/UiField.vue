<template>
  <label class="flex flex-col gap-2">
    <span class="text-sm font-medium text-slate-800">
      {{ label }}
    </span>

    <slot />

    <span v-if="hint" class="text-xs text-slate-500">
      {{ hint }}
    </span>

    <span v-if="error" class="text-xs text-red-600">
      {{ error }}
    </span>
  </label>
</template>

<script setup lang="ts">
defineProps<{
  label: string;
  hint?: string;
  error?: string;
}>();
</script>
