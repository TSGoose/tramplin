<template>
  <UiCard className="p-6">
    <div class="space-y-4">
      <div>
        <h3 class="text-lg font-semibold text-slate-900">Отклик на возможность</h3>
        <p class="mt-2 text-sm text-slate-600">
          Добавь сопроводительное письмо. Это повысит качество отклика.
        </p>
      </div>

      <UiAlert v-if="applicationsStore.errorMessage">
        {{ applicationsStore.errorMessage }}
      </UiAlert>

      <UiAlert v-if="applicationsStore.successMessage" variant="success">
        {{ applicationsStore.successMessage }}
      </UiAlert>

      <UiField label="Сопроводительное письмо">
        <textarea
          v-model="coverLetter"
          rows="5"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
          placeholder="Кратко расскажи, почему тебе интересна эта возможность"
        />
      </UiField>

      <UiButton
        :disabled="applicationsStore.isSubmitting || applicationsStore.hasApplied(opportunityId)"
        fullWidth
        @click="onApply"
      >
        {{
          applicationsStore.hasApplied(opportunityId)
            ? 'Отклик уже отправлен'
            : applicationsStore.isSubmitting
              ? 'Отправка...'
              : 'Отправить отклик'
        }}
      </UiButton>
    </div>
  </UiCard>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import UiAlert from '@/shared/ui/UiAlert.vue';
import UiButton from '@/shared/ui/UiButton.vue';
import UiCard from '@/shared/ui/UiCard.vue';
import UiField from '@/shared/ui/UiField.vue';
import { useApplicationsStore } from '@/features/applications/model/applications.store';

const props = defineProps<{
  opportunityId: number;
}>();

const applicationsStore = useApplicationsStore();
const coverLetter = ref('');

async function onApply(): Promise<void> {
  applicationsStore.clearMessages();

  await applicationsStore.apply({
    opportunity_id: props.opportunityId,
    cover_letter: coverLetter.value.trim().length > 0 ? coverLetter.value.trim() : null,
  });
}
</script>
