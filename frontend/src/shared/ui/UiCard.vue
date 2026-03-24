<template>
  <div class="surface-card" :class="className">
    <slot />
  </div>
</template>

<script setup lang="ts">
defineProps<{
  className?: string;
}>();
</script>
