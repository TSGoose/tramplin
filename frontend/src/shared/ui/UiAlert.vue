<template>
  <div
    class="rounded-xl border px-4 py-3 text-sm"
    :class="variantClass"
  >
    <slot />
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(
  defineProps<{
    variant?: 'error' | 'info' | 'success';
  }>(),
  {
    variant: 'error',
  },
);

const variantClass = computed(() => {
  if (props.variant === 'success') {
    return 'border-green-200 bg-green-50 text-green-700';
  }

  if (props.variant === 'info') {
    return 'border-blue-200 bg-blue-50 text-blue-700';
  }

  return 'border-red-200 bg-red-50 text-red-700';
});
</script>
