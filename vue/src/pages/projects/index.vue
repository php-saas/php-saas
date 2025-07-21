<script setup lang="ts">
import Layout from '@/layouts/settings/layout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { BreadcrumbItem, PaginatedData } from '@/types';
import Container from '@/components/container.vue';
import Heading from '@/components/heading.vue';
import ProjectForm from '@/pages/projects/components/project-form.vue';
import { Button } from '@/components/ui/button';
import { PlusIcon } from 'lucide-vue-next';
import { Project } from '@/types/project';
import { ProjectUser } from '@/types/project-user';
import DataTable from '@/components/data-table.vue';
import { columns } from '@/pages/projects/components/columns';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Settings',
    href: '/settings',
  },
  {
    title: 'Projects',
    href: '/settings/projects',
  },
];

const page = usePage<{
  projects: PaginatedData<Project>;
  invitations: PaginatedData<ProjectUser>;
}>();
</script>

<template>
  <Layout :breadcrumbs="breadcrumbs">
    <Head title="Projects" />

    <Container class="max-w-5xl">
      <div class="flex items-start justify-between">
        <Heading title="Projects" description="Here you can manage your projects" />
        <div class="flex items-center gap-2">
          <ProjectForm>
            <Button>
              <PlusIcon />
              Create project
            </Button>
          </ProjectForm>
        </div>
      </div>
      <DataTable :columns="columns" :paginatedData="page.props.projects" />

      <div v-if="page.props.invitations.data.length > 0">
        <Heading title="Invitations" description="Here you can see the projects you're invited to" />
        <!--        <DataTable columns="{invitationColumns}" paginatedData="{page.props.invitations}" />-->
      </div>
    </Container>
  </Layout>
</template>
