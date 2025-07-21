<script setup lang="ts">
import Heading from '@/components/heading.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/app-layout.vue';
import type { BreadcrumbItem } from '@/types';
import Container from '@/components/container.vue';
import { cn } from '@/lib/utils';
import { BriefcaseIcon, CommandIcon, UserIcon } from 'lucide-vue-next';

const sidebarNavItems: NavItem[] = [
  {
    title: 'Profile',
    href: '/settings/profile',
    icon: UserIcon,
  },
  // <php-saas:projects>
  {
    title: 'Projects',
    href: '/settings/projects',
    icon: BriefcaseIcon,
  },
  // </php-saas:projects>
  // <php-saas:tokens>
  {
    title: 'API Tokens',
    href: '/settings/tokens',
    icon: CommandIcon,
  },
  // </php-saas:tokens>
];

interface Props {
  breadcrumbs?: BreadcrumbItem[];
}

withDefaults(defineProps<Props>(), {
  breadcrumbs: () => [],
});

const currentUrl = window.location.pathname;
</script>

<template>
  <AppLayout :breadcrumbs="$props.breadcrumbs">
    <Container class="flex max-w-5xl flex-col items-start p-4 lg:flex-row lg:gap-5">
      <div class="space-y-5 lg:w-48">
        <nav class="flex flex-row gap-2 lg:flex-col">
          <Link
            v-for="item in sidebarNavItems"
            :key="item.title"
            :href="item.href"
            :class="
              cn(
                'hover:bg-muted flex items-center gap-2 rounded-md px-2 py-1.5 text-sm font-medium',
                item.onlyActivePath ? currentUrl === item.href : currentUrl.startsWith(item.href) ? 'bg-muted' : '',
              )
            "
          >
            <component :is="item.icon" class="h-4 w-4" />
            {{ item.title }}
          </Link>
        </nav>
      </div>
      <div class="w-full flex-1">
        <slot />
      </div>
    </Container>
  </AppLayout>
</template>
