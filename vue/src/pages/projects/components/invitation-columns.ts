import { ColumnDef } from '@tanstack/vue-table';
import { ProjectUser } from '@/types/project-user';
import { h } from 'vue';
import { Badge } from '@/components/ui/badge';
import InvitationActions from '@/pages/projects/components/invitation-actions.vue';

export const invitationColumns: ColumnDef<ProjectUser>[] = [
  {
    accessorKey: 'project_name',
    header: 'Project name',
    enableColumnFilter: true,
    enableSorting: true,
  },
  {
    accessorKey: 'role',
    header: 'Role',
    enableColumnFilter: true,
    enableSorting: true,
    cell: ({ row }) => {
      return h(Badge, { variant: 'outline' }, () => row.original.role);
    },
  },
  {
    id: 'actions',
    enableColumnFilter: false,
    enableSorting: false,
    cell: ({ row }) => {
      return h(InvitationActions, { invitation: row.original });
    },
  },
];
