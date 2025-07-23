<script setup lang="ts">
import Layout from '@/layouts/settings/layout.vue';
import { BreadcrumbItem, PaginatedData } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { Token } from '@/types/token';
import Container from '@/components/container.vue';
import Heading from '@/components/heading.vue';
import DataTable from '@/components/data-table.vue';
import { columns } from '@/pages/tokens/components/columns';
import CreateToken from '@/pages/tokens/components/create-token.vue';
import { Button } from '@/components/ui/button';
import { PlusIcon } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Settings',
    href: '/settings',
  },
  {
    title: 'Tokens',
    href: '/setting/tokens',
  },
];

const page = usePage<{
  tokens: PaginatedData<Token>;
}>();
</script>

<template>
  <Layout :breadcrumbs="breadcrumbs">
    <Head title="API Tokens" />
    <Container class="max-w-5xl">
      <div class="flex items-start justify-between">
        <Heading title="API Tokens" description="Here you can manage API tokens" />
        <div class="flex items-center gap-2">
          <CreateToken>
            <Button>
              <PlusIcon />
              Create token
            </Button>
          </CreateToken>
        </div>
      </div>
      <DataTable :columns="columns" :paginatedData="page.props.tokens" />
    </Container>
  </Layout>
</template>
