<script setup lang="ts">
import AppLayout from "@/layouts/app/app-sidebar-layout.vue";
import type { BreadcrumbItem } from "@/types";
import Toast from '@/components/toast.vue';

interface Props {
  breadcrumbs?: BreadcrumbItem[];
}

withDefaults(defineProps<Props>(), {
  breadcrumbs: () => [],
});
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <slot />
    <Toast />
  </AppLayout>
</template>
