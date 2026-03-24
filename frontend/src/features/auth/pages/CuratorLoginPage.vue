<template>
  <section class="mx-auto flex min-h-[calc(100vh-81px)] max-w-7xl items-center justify-center px-6 py-16">
    <LoginCard
      title="Вход куратора"
      description="Отдельный контур входа для куратора и администратора системы."
      submit-label="Войти как куратор"
    />
  </section>
</template>

<script setup lang="ts">
import LoginCard from '@/features/auth/components/LoginCard.vue';
</script>
