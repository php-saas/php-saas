<script setup lang="ts">
import AppContent from "@/components/app-content.vue";
import AppHeader from "@/components/app-header.vue";
import AppShell from "@/components/app-shell.vue";
import type { BreadcrumbItem } from "@/types";

interface Props {
  breadcrumbs?: BreadcrumbItem[];
}

withDefaults(defineProps<Props>(), {
  breadcrumbs: () => [],
});
</script>

<template>
  <AppShell class="flex-col">
    <AppHeader :breadcrumbs="breadcrumbs" />
    <AppContent>
      <slot />
    </AppContent>
  </AppShell>
</template>
