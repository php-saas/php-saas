import { ColumnDef } from '@tanstack/vue-table';
import { Token } from '@/types/token';
import { h } from 'vue';
import DateTime from '@/components/date-time.vue';
import Actions from '@/pages/tokens/components/actions.vue';

export const columns: ColumnDef<Token>[] = [
  {
    accessorKey: 'name',
    header: 'Name',
    enableColumnFilter: true,
    enableSorting: true,
  },
  {
    accessorKey: 'abilities',
    header: 'Abilities',
    enableColumnFilter: true,
    enableSorting: true,
  },
  {
    accessorKey: 'created_at',
    header: 'Created at',
    enableColumnFilter: true,
    enableSorting: true,
    cell: ({ row }) => {
      return h(DateTime, { date: row.original.created_at });
    },
  },
  {
    id: 'actions',
    enableColumnFilter: false,
    enableSorting: false,
    cell: ({ row }) => h('div', { class: 'flex items-center justify-end' }, [h(Actions, { token: row.original })]),
  },
];
