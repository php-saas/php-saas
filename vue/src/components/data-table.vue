<script lang="ts" setup>
import { computed } from 'vue';
import { useVueTable, getCoreRowModel, FlexRender } from '@tanstack/vue-table';
import { router } from '@inertiajs/vue3';

import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { ChevronsLeft, ChevronLeft, ChevronRight, ChevronsRight } from 'lucide-vue-next';
import { cn } from '@/lib/utils';
import { PaginatedData } from '@/types';

interface DataTableProps<TData> {
  columns: any[];
  paginatedData?: PaginatedData<TData>;
  data?: TData[];
  className?: string;
  modal?: boolean;
  onPageChange?: (page: number) => void;
  isFetching?: boolean;
  isLoading?: boolean;
}

const props = defineProps<DataTableProps<any>>();

const tableData = computed(() => props.paginatedData?.data || props.data || []);
const table = useVueTable({
  data: tableData,
  columns: props.columns,
  getCoreRowModel: getCoreRowModel(),
});

const extraClasses = computed(() => props.modal && '');

function handlePageChange(url: string) {
  if (props.onPageChange) {
    const urlObj = new URL(url);
    const page = urlObj.searchParams.get('page');
    props.onPageChange(page ? parseInt(page) : 1);
  } else {
    router.get(url, {}, { preserveState: true });
  }
}
</script>

<template>
  <div :class="cn('bg-card text-card-foreground relative overflow-hidden rounded-xl border shadow-sm', className, extraClasses)">
    <div v-if="isLoading" class="absolute top-0 right-0 left-0 h-[2px] overflow-hidden">
      <div class="animate-loading-bar bg-primary absolute inset-0 w-full" />
    </div>

    <Table>
      <TableHeader>
        <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
          <TableHead v-for="header in headerGroup.headers" :key="header.id">
            <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header" :props="header.getContext()" />
          </TableHead>
        </TableRow>
      </TableHeader>

      <TableBody>
        <template v-if="table.getRowModel().rows.length">
          <TableRow v-for="row in table.getRowModel().rows" :key="row.id" :data-state="row.getIsSelected() ? 'selected' : undefined">
            <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
              <FlexRender v-if="cell.column.columnDef.cell" :render="cell.column.columnDef.cell" :props="cell.getContext()" />
            </TableCell>
          </TableRow>
        </template>
        <template v-else>
          <TableRow>
            <TableCell :colspan="columns.length" class="h-24 text-center">No results.</TableCell>
          </TableRow>
        </template>
      </TableBody>
    </Table>

    <div v-if="paginatedData" class="flex items-center justify-between border-t px-4 py-3">
      <div class="text-muted-foreground flex items-center text-sm">
        <span v-if="paginatedData.meta.from && paginatedData.meta.to">
          Showing {{ paginatedData.meta.from }} to {{ paginatedData.meta.to
          }}<span v-if="paginatedData.meta.total"> of {{ paginatedData.meta.total }}</span> results
        </span>
      </div>

      <div class="flex items-center space-x-2">
        <Button
          variant="outline"
          size="sm"
          @click="() => props.paginatedData?.links.first && handlePageChange(props.paginatedData?.links.first)"
          :disabled="!paginatedData.links.first || isFetching"
        >
          <ChevronsLeft class="h-4 w-4" />
        </Button>
        <Button
          variant="outline"
          size="sm"
          @click="() => props.paginatedData?.links.prev && handlePageChange(props.paginatedData?.links.prev)"
          :disabled="!paginatedData.links.prev || isFetching"
        >
          <ChevronLeft class="h-4 w-4" />
        </Button>
        <div class="flex items-center text-sm font-medium">
          Page {{ props.paginatedData?.meta.current_page }}<span v-if="props.paginatedData?.meta.last_page"> of {{ props.paginatedData?.meta.last_page }}</span>
        </div>
        <Button
          variant="outline"
          size="sm"
          @click="() => props.paginatedData?.links.next && handlePageChange(props.paginatedData?.links.next)"
          :disabled="!paginatedData.links.next || isFetching"
        >
          <ChevronRight class="h-4 w-4" />
        </Button>
        <Button
          variant="outline"
          size="sm"
          @click="() => props.paginatedData?.links.last && handlePageChange(props.paginatedData?.links.last)"
          :disabled="!paginatedData.links.last || isFetching"
        >
          <ChevronsRight class="h-4 w-4" />
        </Button>
      </div>
    </div>
  </div>
</template>
