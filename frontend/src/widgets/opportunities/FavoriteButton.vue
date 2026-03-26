<template>
  <UiButton
    :variant="isActive ? 'secondary' : 'ghost'"
    :disabled="favoritesStore.isMutating"
    @click="onClick"
  >
    {{ isActive ? 'В избранном' : 'В избранное' }}
  </UiButton>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useAuthStore } from '@/features/auth/model/auth.store';
import { useFavoritesStore } from '@/features/favorites/model/favorites.store';
import UiButton from '@/shared/ui/UiButton.vue';

const props = defineProps<{
  opportunityId: number;
}>();

const authStore = useAuthStore();
const favoritesStore = useFavoritesStore();

const isActive = computed(() => favoritesStore.isFavorite(props.opportunityId));

async function onClick(): Promise<void> {
  if (!authStore.isAuthenticated || authStore.user?.role !== 'applicant') {
    return;
  }

  await favoritesStore.toggle(props.opportunityId);
}
</script>
