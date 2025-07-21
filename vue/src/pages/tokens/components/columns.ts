import { ColumnDef } from '@tanstack/vue-table';
import { Token } from '@/types/token';
import { h } from 'vue';
import { Badge } from '@/components/ui/badge';

export const columns: ColumnDef<Token>[] = [
  {
    accessorKey: 'name',
    header: 'Name',
    enableColumnFilter: true,
    enableSorting: true
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
  },
  {
    id: 'actions',
    enableColumnFilter: false,
    enableSorting: false,
  },
];
