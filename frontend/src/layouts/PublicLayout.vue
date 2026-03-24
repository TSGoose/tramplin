<template>
  <div class="min-h-screen bg-slate-50">
    <AppHeader />
    <main>
      <RouterView />
    </main>
  </div>
</template>

<script setup lang="ts">
import AppHeader from '@/widgets/header/AppHeader.vue';
</script>
