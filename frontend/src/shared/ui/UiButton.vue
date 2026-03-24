
<template>
  <button
    :type="type"
    :disabled="disabled"
    :class="[
      'inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60',
      variantClass,
      fullWidth ? 'w-full' : '',
    ]"
  >
    <slot />
  </button>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(
  defineProps<{
    type?: 'button' | 'submit' | 'reset';
    variant?: 'primary' | 'secondary' | 'ghost';
    fullWidth?: boolean;
    disabled?: boolean;
  }>(),
  {
    type: 'button',
    variant: 'primary',
    fullWidth: false,
    disabled: false,
  },
);

const variantClass = computed(() => {
  switch (props.variant) {
    case 'secondary':
      return 'border border-slate-200 bg-white text-slate-900 hover:bg-slate-50';
    case 'ghost':
      return 'bg-transparent text-slate-700 hover:bg-slate-100';
    default:
      return 'bg-blue-600 text-white shadow-sm hover:bg-blue-700';
  }
});
</script>
