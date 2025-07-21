<script setup lang="ts">
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { BreadcrumbItem } from '@/types';
import Layout from '@/layouts/settings/layout.vue';
import { Head } from '@inertiajs/vue3';
import Container from '@/components/container.vue';
import Heading from '@/components/heading.vue';
import UpdateProfile from '@/pages/profile/components/update-profile.vue';
import DeleteUser from '@/pages/profile/components/delete-user.vue';
import UpdatePassword from '@/pages/profile/components/update-password.vue';
import TwoFactor from '@/pages/profile/components/two-factor.vue';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Settings',
    href: '/settings',
  },
  {
    title: 'Profile',
    href: '/settings/profile',
  },
];
</script>

<template>
  <Layout :breadcrumbs="breadcrumbs">
    <Head title="Profile settings" />
    <Container class="max-w-5xl">
      <Heading title="Profile settings" description="Manage your profile settings." />

      <Tabs default-value="info">
        <TabsList>
          <TabsTrigger value="info">Info</TabsTrigger>
          <TabsTrigger value="password">Password</TabsTrigger>
          <TabsTrigger value="two_factor">Two Factor</TabsTrigger>
        </TabsList>
        <TabsContent value="info" class="space-y-4">
          <UpdateProfile />
          <DeleteUser />
        </TabsContent>
        <TabsContent value="password">
          <UpdatePassword />
        </TabsContent>
        <TabsContent value="two_factor">
          <TwoFactor />
        </TabsContent>
      </Tabs>
    </Container>
  </Layout>
</template>
