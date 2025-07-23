<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { ProjectUser } from '@/types/project-user';
import { ColumnDef } from '@tanstack/vue-table';
import { h } from 'vue';
import { Sheet, SheetClose, SheetContent, SheetDescription, SheetFooter, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import DataTable from '@/components/data-table.vue';
import { Button } from '@/components/ui/button';
import Invite from '@/pages/projects/components/invite.vue';
import { Project } from '@/types/project';
import UsersActions from '@/pages/projects/components/users-actions.vue';

const columns: ColumnDef<ProjectUser>[] = [
  {
    accessorKey: 'email',
    header: 'Email',
    enableColumnFilter: true,
    enableSorting: true,
  },
  {
    id: 'role',
    header: 'Role',
    enableColumnFilter: false,
    enableSorting: false,
    cell: ({ row }) => h(Badge, { variant: 'outline' }, () => row.original.role),
  },
  {
    id: 'status',
    header: 'Status',
    enableColumnFilter: false,
    enableSorting: false,
    cell: ({ row }) => h(Badge, { variant: 'outline' }, () => (row.original.type === 'user' ? 'registered' : 'invited')),
  },
  {
    id: 'actions',
    enableColumnFilter: false,
    enableSorting: false,
    cell: ({ row }) =>
      h(UsersActions, {
        projectId: row.original.project_id,
        user: row.original,
      }),
  },
];

const props = defineProps<{
  project: Project;
}>();
</script>

<template>
  <Sheet>
    <SheetTrigger as-child>
      <slot />
    </SheetTrigger>
    <SheetContent class="sm:max-w-2xl">
      <SheetHeader>
        <SheetTitle>Project users</SheetTitle>
        <SheetDescription>Here you can manage project users</SheetDescription>
      </SheetHeader>
      <div class="p-4">
        <DataTable :columns="columns" :data="[...(props.project.owner ? [props.project.owner] : []), ...(props.project.users || [])]" />
      </div>
      <SheetFooter>
        <div class="flex items-center gap-2">
          <SheetClose as-child>
            <Button variant="outline">Close</Button>
          </SheetClose>
          <Invite :project="props.project">
            <Button>Invite</Button>
          </Invite>
        </div>
      </SheetFooter>
    </SheetContent>
  </Sheet>
</template>
