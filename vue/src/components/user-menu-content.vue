<script setup lang="ts">
import UserInfo from '@/components/user-info.vue';
import { DropdownMenuGroup, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';
import type { User } from '@/types/user';
import { Link, router } from '@inertiajs/vue3';
import { LogOutIcon, SettingsIcon, CreditCardIcon } from 'lucide-vue-next';
import AppearanceTabs from '@/components/appearance-tabs.vue';

interface Props {
  user: User;
}

const handleLogout = () => {
  router.flushAll();
};

defineProps<Props>();
</script>

<template>
  <DropdownMenuLabel class="p-0 font-normal">
    <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
      <UserInfo :user="user" :show-email="true" />
    </div>
  </DropdownMenuLabel>
  <DropdownMenuSeparator />
  <AppearanceTabs />
  <DropdownMenuSeparator />
  <DropdownMenuGroup>
    <DropdownMenuItem :as-child="true">
      <Link class="block w-full" href="/settings/profile" prefetch as="button">
        <SettingsIcon class="mr-2" />
        Profile
      </Link>
    </DropdownMenuItem>
    <!--<php-saas:billing>-->
    <DropdownMenuItem :as-child="true">
      <a class="block w-full" href="/billing">
        <CreditCardIcon class="mr-2" />
        Billing
      </a>
    </DropdownMenuItem>
    <!--</php-saas:billing>-->
  </DropdownMenuGroup>
  <DropdownMenuSeparator />
  <DropdownMenuItem :as-child="true">
    <Link class="block w-full" method="post" href="/logout" @click="handleLogout" as="button">
      <LogOutIcon class="mr-2" />
      Log out
    </Link>
  </DropdownMenuItem>
</template>
