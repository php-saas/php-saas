<script setup lang="ts">
import { useAppearance } from "@/composables/use-appearance";
import { Monitor, Moon, Sun } from "lucide-vue-next";

const { appearance, updateAppearance } = useAppearance();

const tabs = [
  { value: "light", Icon: Sun, label: "Light" },
  { value: "dark", Icon: Moon, label: "Dark" },
  { value: "system", Icon: Monitor, label: "System" },
] as const;
</script>

<template>
  <div
    class="w-full flex items-center justify-between gap-1 rounded-lg bg-neutral-100 p-1 dark:bg-neutral-800"
  >
    <button
      v-for="{ value, Icon } in tabs"
      :key="value"
      @click="updateAppearance(value)"
      :class="[
        'w-full flex items-center justify-center rounded-md px-3.5 py-1.5 transition-colors',
        appearance === value
          ? 'bg-white shadow-xs dark:bg-neutral-700 dark:text-neutral-100'
          : 'text-neutral-500 hover:bg-neutral-200/60 hover:text-black dark:text-neutral-400 dark:hover:bg-neutral-700/60',
      ]"
    >
      <component :is="Icon" class="-ml-1 h-4 w-4" />
    </button>
  </div>
</template>
