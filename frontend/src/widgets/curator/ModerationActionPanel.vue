<template>
  <UiCard className="p-4">
    <div class="space-y-3">
      <UiField label="Комментарий к решению">
        <textarea
          v-model="comment"
          rows="3"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
          placeholder="Краткий комментарий для работодателя"
        />
      </UiField>

      <div class="flex flex-wrap gap-2">
        <UiButton :disabled="disabled" @click="$emit('approve', normalizedComment)">
          Одобрить
        </UiButton>
        <UiButton variant="secondary" :disabled="disabled" @click="$emit('needs-revision', normalizedComment)">
          На доработку
        </UiButton>
        <UiButton variant="ghost" :disabled="disabled" @click="$emit('reject', normalizedComment)">
          Отклонить
        </UiButton>
      </div>
    </div>
  </UiCard>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';
import UiButton from '@/shared/ui/UiButton.vue';
import UiCard from '@/shared/ui/UiCard.vue';
import UiField from '@/shared/ui/UiField.vue';

defineProps<{
  disabled?: boolean;
}>();

defineEmits<{
  approve: [comment: string | null];
  'needs-revision': [comment: string | null];
  reject: [comment: string | null];
}>();

const comment = ref('');

const normalizedComment = computed<string | null>(() => {
  const value = comment.value.trim();
  return value.length > 0 ? value : null;
});
</script>
