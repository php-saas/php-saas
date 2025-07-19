<script setup lang="ts">
import AppLogoIcon from "@/components/app-logo-icon.vue";

defineProps<{
  title?: string;
  description?: string;
}>();
</script>

<template>
  <div
    class="relative mx-auto flex h-dvh max-w-sm flex-col items-center justify-center px-8 sm:px-0 lg:grid lg:max-w-none lg:grid-cols-2 lg:px-0"
  >
    <div
      class="bg-muted text-foreground relative hidden h-full flex-col border-r p-10 lg:flex"
    >
      <div class="bg-muted absolute inset-0" />
      <a href="/" class="relative z-50 flex items-center text-lg font-medium">
        <AppLogoIcon class="mr-2 size-8" />
        PHP-SaaS
      </a>
      <div class="relative z-20 mt-auto">
        <blockquote class="space-y-2">
          <p class="text-foreground text-lg">
            &ldquo;If you’re building a lot of software, you need a good starter
            kit.&rdquo;
          </p>
          <footer class="text-muted-foreground text-sm">Saeed Vaziry</footer>
        </blockquote>
      </div>
    </div>
    <div class="w-full lg:p-8">
      <div
        class="mx-auto flex w-full flex-col justify-center space-y-6 lg:w-[350px]"
      >
        <a
          href="/"
          class="relative z-20 flex items-center justify-center lg:hidden"
        >
          <AppLogoIcon class="size-10" />
        </a>
        <div class="flex flex-col items-center gap-2 text-center">
          <h1 class="text-xl font-medium">{{ title }}</h1>
          <p class="text-muted-foreground text-sm text-balance">
            {{ description }}
          </p>
        </div>
        <slot />
      </div>
    </div>
  </div>
</template>
