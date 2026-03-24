<template>
  <input
    :type="type"
    :value="modelValue"
    :placeholder="placeholder"
    :autocomplete="autocomplete"
    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
    @input="onInput"
  />
</template>

<script setup lang="ts">
const props = withDefaults(
  defineProps<{
    modelValue: string;
    type?: string;
    placeholder?: string;
    autocomplete?: string;
  }>(),
  {
    type: 'text',
    placeholder: '',
    autocomplete: 'off',
  },
);

const emit = defineEmits<{
  'update:modelValue': [value: string];
}>();

function onInput(event: Event): void {
  const target = event.target as HTMLInputElement;
  emit('update:modelValue', target.value);
}
</script>
