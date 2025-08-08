<script setup lang="ts">
import AppContent from "@/components/app-content.vue";
import AppShell from "@/components/app-shell.vue";
import AppSidebar from "@/components/app-sidebar.vue";
import AppSidebarHeader from "@/components/app-sidebar-header.vue";
import type { BreadcrumbItem } from "@/types";

interface Props {
  breadcrumbs?: BreadcrumbItem[];
}

withDefaults(defineProps<Props>(), {
  breadcrumbs: () => [],
});
</script>

<template>
  <AppShell variant="sidebar">
    <AppSidebar />
    <AppContent variant="sidebar" class="overflow-x-hidden">
      <AppSidebarHeader :breadcrumbs="breadcrumbs" />
      <slot />
    </AppContent>
  </AppShell>
</template>
