import { ColumnDef } from '@tanstack/vue-table';
import { Project } from '@/types/project';
import { h } from 'vue';
import { Badge } from '@/components/ui/badge';
import DateTime from '@/components/date-time.vue';
import Actions from '@/pages/projects/components/actions.vue';

export const columns: ColumnDef<Project>[] = [
  {
    accessorKey: 'name',
    header: 'Name',
    enableColumnFilter: true,
    enableSorting: true,
    cell: ({ row }) =>
      h(
        'div',
        {
          class: 'flex items-center gap-2',
        },
        [h('span', {}, row.original.name), row.original.is_current ? h(Badge, {}, () => `Current`) : null],
      ),
  },
  {
    accessorKey: 'role',
    header: 'Role',
    enableColumnFilter: true,
    enableSorting: true,
    cell: ({ row }) =>
      h(
        Badge,
        {
          variant: 'outline',
        },
        () => row.original.role,
      ),
  },
  {
    accessorKey: 'created_at',
    header: 'Created at',
    enableColumnFilter: true,
    enableSorting: true,
    cell: ({ row }) =>
      h(DateTime, {
        date: row.original.created_at,
      }),
  },
  {
    id: 'actions',
    enableColumnFilter: false,
    enableSorting: false,
    cell: ({ row }) => h('div', { class: 'flex items-center justify-end' }, [h(Actions, { project: row.original })]),
  },
];
