<script setup lang="ts">
import AuthLayout from "@/layouts/auth/auth-split-layout.vue";

defineProps<{
  title?: string;
  description?: string;
}>();
</script>

<template>
  <AuthLayout :title="title" :description="description">
    <slot />
  </AuthLayout>
</template>
