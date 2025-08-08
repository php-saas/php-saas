<script setup lang="ts">
import NavFooter from "@/components/nav-footer.vue";
import NavMain from "@/components/nav-main.vue";
import NavUser from "@/components/nav-user.vue";
import {
  Sidebar,
  SidebarContent,
  SidebarFooter,
  SidebarHeader,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
} from "@/components/ui/sidebar";
import { type NavItem } from "@/types";
import { Link } from "@inertiajs/vue3";
import { BookOpen, Folder, LayoutGrid, SettingsIcon, CreditCardIcon } from "lucide-vue-next";
import AppLogo from "./app-logo.vue";

const mainNavItems: NavItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
    icon: LayoutGrid,
  },
  {
    title: 'Settings',
    href: '/settings',
    icon: SettingsIcon,
  },
];

const footerNavItems: NavItem[] = [
  // <php-saas:billing>
  {
    title: 'Billing',
    href: '/billing',
    icon: CreditCardIcon,
  },
  // </php-saas:billing>
  {
    title: 'Repository',
    href: 'https://github.com/php-saas',
    icon: Folder,
    external: true,
  },
  {
    title: 'Documentation',
    href: 'https://php-saas.com',
    icon: BookOpen,
    external: true,
  },
];
</script>

<template>
  <Sidebar collapsible="icon" variant="floating">
    <SidebarHeader>
      <SidebarMenu>
        <SidebarMenuItem>
          <SidebarMenuButton size="lg" as-child>
            <Link href="/dashboard">
              <AppLogo />
            </Link>
          </SidebarMenuButton>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarHeader>

    <SidebarContent>
      <NavMain :items="mainNavItems" />
    </SidebarContent>

    <SidebarFooter>
      <NavFooter :items="footerNavItems" />
      <NavUser />
    </SidebarFooter>
  </Sidebar>
  <slot />
</template>
