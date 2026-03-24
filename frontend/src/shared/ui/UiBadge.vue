
<template>
  <span
    class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-medium"
    :class="badgeClass"
  >
    <slot />
  </span>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(
  defineProps<{
    variant?: 'default' | 'success' | 'warning';
  }>(),
  {
    variant: 'default',
  },
);

const badgeClass = computed(() => {
  if (props.variant === 'success') {
    return 'border-green-200 bg-green-50 text-green-700';
  }

  if (props.variant === 'warning') {
    return 'border-amber-200 bg-amber-50 text-amber-700';
  }

  return 'border-slate-200 bg-slate-50 text-slate-700';
});
</script>
