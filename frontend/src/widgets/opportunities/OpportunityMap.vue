<template>
  <div class="grid gap-6 xl:grid-cols-[1.3fr_0.7fr]">
    <BaseYandexMap
      :points="mappedPoints"
      :selected-id="selectedOpportunityId"
      :api-key="apiKey"
      @select="onSelect"
    />

    <div>
      <OpportunityMapCard
        v-if="selectedOpportunity"
        :opportunity="selectedOpportunity"
      />

      <UiCard v-else className="p-6">
        <p class="text-sm text-slate-600">
          Выбери маркер на карте, чтобы посмотреть подробности.
        </p>
      </UiCard>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import type { Opportunity } from '@/entities/opportunity/model/types';
import UiCard from '@/shared/ui/UiCard.vue';
import BaseYandexMap from '@/widgets/map/BaseYandexMap.vue';
import OpportunityMapCard from '@/widgets/opportunities/OpportunityMapCard.vue';

const props = defineProps<{
  opportunities: Opportunity[];
  apiKey?: string;
}>();

const apiKey = import.meta.env.VITE_YANDEX_MAPS_API_KEY || undefined;

const selectedOpportunityId = ref<number | null>(null);

const mappedPoints = computed(() =>
  props.opportunities
    .filter((item) => item.latitude !== null && item.longitude !== null)
    .map((item) => ({
      id: item.id,
      latitude: Number(item.latitude),
      longitude: Number(item.longitude),
    })),
);

const selectedOpportunity = computed(() =>
  props.opportunities.find((item) => item.id === selectedOpportunityId.value) ?? null,
);

watch(
  () => props.opportunities,
  (value) => {
    if (value.length > 0 && selectedOpportunityId.value === null) {
      selectedOpportunityId.value = value[0].id;
    }
  },
  { immediate: true },
);

function onSelect(id: number): void {
  selectedOpportunityId.value = id;
}
</script>
