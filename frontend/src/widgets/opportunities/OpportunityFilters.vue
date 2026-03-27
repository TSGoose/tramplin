<template>
  <UiCard className="p-6">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
      <UiField label="Поиск">
        <UiInput
          v-model="localFilters.search"
          placeholder="Название, компания..."
        />
      </UiField>

      <UiField label="Тип">
        <select
          v-model="localFilters.type"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
        >
          <option value="">Все</option>
          <option value="vacancy">Вакансии</option>
          <option value="internship">Стажировки</option>
          <option value="mentorship">Менторство</option>
          <option value="event">События</option>
        </select>
      </UiField>

      <UiField label="Формат">
        <select
          v-model="localFilters.work_format"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
        >
          <option value="">Все</option>
          <option value="office">Офис</option>
          <option value="remote">Удаленно</option>
          <option value="hybrid">Гибрид</option>
          <option value="online">Онлайн</option>
        </select>
      </UiField>

      <UiField label="Город">
        <UiInput
          v-model="localFilters.city"
          placeholder="Москва"
        />
      </UiField>

      <UiField label="Тег">
        <select
          v-model="localFilters.tag"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
        >
          <option value="">Все</option>
          <option v-for="tag in tags" :key="tag.id" :value="tag.slug">
            {{ tag.name }}
          </option>
        </select>
      </UiField>
    </div>

    <div class="mt-5 flex flex-wrap items-center gap-3">
      <UiButton @click="onSubmit">Применить фильтры</UiButton>
      <UiButton variant="secondary" @click="onReset">Сбросить</UiButton>
    </div>
  </UiCard>
</template>

<script setup lang="ts">
import { reactive, watch } from 'vue';
import type { OpportunityFilters, OpportunityTag } from '@/entities/opportunity/model/types';
import UiButton from '@/shared/ui/UiButton.vue';
import UiCard from '@/shared/ui/UiCard.vue';
import UiField from '@/shared/ui/UiField.vue';
import UiInput from '@/shared/ui/UiInput.vue';

const props = defineProps<{
  modelValue: OpportunityFilters;
  tags: OpportunityTag[];
}>();

const emit = defineEmits<{
  'update:modelValue': [value: OpportunityFilters];
  submit: [];
  reset: [];
}>();

const localFilters = reactive<OpportunityFilters>({
  search: '',
  type: '',
  work_format: '',
  city: '',
  tag: '',
});

watch(
  () => props.modelValue,
  (value) => {
    localFilters.search = value.search;
    localFilters.type = value.type;
    localFilters.work_format = value.work_format;
    localFilters.city = value.city;
    localFilters.tag = value.tag;
  },
  { immediate: true, deep: true },
);

function onSubmit(): void {
  emit('update:modelValue', {
    search: localFilters.search,
    type: localFilters.type,
    work_format: localFilters.work_format,
    city: localFilters.city,
    tag: localFilters.tag,
  });

  emit('submit');
}

function onReset(): void {
  localFilters.search = '';
  localFilters.type = '';
  localFilters.work_format = '';
  localFilters.city = '';
  localFilters.tag = '';

  emit('update:modelValue', {
    search: '',
    type: '',
    work_format: '',
    city: '',
    tag: '',
  });

  emit('reset');
}
</script>
