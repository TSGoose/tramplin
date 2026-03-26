<template>
  <UiCard className="p-6">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
      <UiField label="Поиск">
        <UiInput
          :model-value="modelValue.search"
          placeholder="Название, компания..."
          @update:model-value="updateField('search', $event)"
        />
      </UiField>

      <UiField label="Тип">
        <select
          :value="modelValue.type"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
          @change="updateField('type', getSelectValue($event))"
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
          :value="modelValue.work_format"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
          @change="updateField('work_format', getSelectValue($event))"
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
          :model-value="modelValue.city"
          placeholder="Москва"
          @update:model-value="updateField('city', $event)"
        />
      </UiField>

      <UiField label="Тег">
        <select
          :value="modelValue.tag"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
          @change="updateField('tag', getSelectValue($event))"
        >
          <option value="">Все</option>
          <option v-for="tag in tags" :key="tag.id" :value="tag.slug">
            {{ tag.name }}
          </option>
        </select>
      </UiField>
    </div>

    <div class="mt-5 flex flex-wrap items-center gap-3">
      <UiButton @click="$emit('submit')">Применить фильтры</UiButton>
      <UiButton variant="secondary" @click="$emit('reset')">Сбросить</UiButton>
    </div>
  </UiCard>
</template>

<script setup lang="ts">
import type { OpportunityFilters, OpportunityTag } from '@/entities/opportunity/model/types';
import UiButton from '@/shared/ui/UiButton.vue';
import UiCard from '@/shared/ui/UiCard.vue';
import UiField from '@/shared/ui/UiField.vue';
import UiInput from '@/shared/ui/UiInput.vue';

const emit = defineEmits<{
  submit: [];
  reset: [];
  'update:modelValue': [value: OpportunityFilters];
}>();

const props = defineProps<{
  modelValue: OpportunityFilters;
  tags: OpportunityTag[];
}>();

function updateField<K extends keyof OpportunityFilters>(key: K, value: OpportunityFilters[K]): void {
  emit('update:modelValue', {
    ...props.modelValue,
    [key]: value,
  });
}

function getSelectValue(event: Event): string {
  return (event.target as HTMLSelectElement).value;
}
</script>
