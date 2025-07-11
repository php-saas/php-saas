import { ColumnDef, flexRender, getCoreRowModel, useReactTable } from '@tanstack/react-table';
import { ChevronLeft, ChevronRight, ChevronsLeft, ChevronsRight } from 'lucide-react';
import { router } from '@inertiajs/react';

import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import { PaginatedData } from '@/types';

interface DataTableProps<TData, TValue> {
  columns: ColumnDef<TData, TValue>[];
  paginatedData?: PaginatedData<TData>;
  data?: TData[];
  className?: string;
  modal?: boolean;
  onPageChange?: (page: number) => void;
  isFetching?: boolean;
  isLoading?: boolean;
}

export function DataTable<TData, TValue>({
  columns,
  paginatedData,
  data,
  className,
  modal,
  onPageChange,
  isFetching,
  isLoading,
}: DataTableProps<TData, TValue>) {
  // Use paginatedData.data if available, otherwise fall back to data prop
  const tableData = paginatedData?.data || data || [];

  const table = useReactTable({
    data: tableData,
    columns,
    getCoreRowModel: getCoreRowModel(),
  });

  const extraClasses = modal && '';

  const handlePageChange = (url: string) => {
    if (onPageChange) {
      // Use custom page change handler (for axios/API calls)
      const urlObj = new URL(url);
      const page = urlObj.searchParams.get('page');
      if (page) {
        onPageChange(parseInt(page));
        return;
      }

      onPageChange(1);
    } else {
      // Use Inertia router for server-side rendered pages
      router.get(url, {}, { preserveState: true });
    }
  };

  return (
    <div className={cn('bg-card text-card-foreground relative overflow-hidden rounded-xl border shadow-sm', className, extraClasses)}>
      {isLoading && (
        <div className="absolute top-0 right-0 left-0 h-[2px] overflow-hidden">
          <div className="animate-loading-bar bg-primary absolute inset-0 w-full" />
        </div>
      )}
      <Table>
        <TableHeader>
          {table.getHeaderGroups().map((headerGroup) => (
            <TableRow key={headerGroup.id}>
              {headerGroup.headers.map((header) => {
                return (
                  <TableHead key={header.id}>
                    {header.isPlaceholder ? null : flexRender(header.column.columnDef.header, header.getContext())}
                  </TableHead>
                );
              })}
            </TableRow>
          ))}
        </TableHeader>
        <TableBody>
          {table.getRowModel().rows?.length ? (
            table.getRowModel().rows.map((row) => (
              <TableRow key={row.id} data-state={row.getIsSelected() && 'selected'}>
                {row.getVisibleCells().map((cell) => (
                  <TableCell key={cell.id}>{flexRender(cell.column.columnDef.cell, cell.getContext())}</TableCell>
                ))}
              </TableRow>
            ))
          ) : (
            <TableRow>
              <TableCell colSpan={columns.length} className="h-24 text-center">
                No results.
              </TableCell>
            </TableRow>
          )}
        </TableBody>
      </Table>

      {paginatedData && (
        <div className="flex items-center justify-between border-t px-4 py-3">
          <div className="text-muted-foreground flex items-center text-sm">
            {paginatedData.meta.from && paginatedData.meta.to && (
              <span>
                Showing {paginatedData.meta.from} to {paginatedData.meta.to}
                {paginatedData.meta.total && ` of ${paginatedData.meta.total}`} results
              </span>
            )}
          </div>

          <div className="flex items-center space-x-2">
            <Button
              variant="outline"
              size="sm"
              onClick={() => paginatedData.links.first && handlePageChange(paginatedData.links.first)}
              disabled={!paginatedData.links.first || isFetching}
            >
              <ChevronsLeft className="h-4 w-4" />
            </Button>

            <Button
              variant="outline"
              size="sm"
              onClick={() => paginatedData.links.prev && handlePageChange(paginatedData.links.prev)}
              disabled={!paginatedData.links.prev || isFetching}
            >
              <ChevronLeft className="h-4 w-4" />
            </Button>

            <div className="flex items-center text-sm font-medium">
              Page {paginatedData.meta.current_page}
              {paginatedData.meta.last_page && ` of ${paginatedData.meta.last_page}`}
            </div>

            <Button
              variant="outline"
              size="sm"
              onClick={() => paginatedData.links.next && handlePageChange(paginatedData.links.next)}
              disabled={!paginatedData.links.next || isFetching}
            >
              <ChevronRight className="h-4 w-4" />
            </Button>

            <Button
              variant="outline"
              size="sm"
              onClick={() => paginatedData.links.last && handlePageChange(paginatedData.links.last)}
              disabled={!paginatedData.links.last || isFetching}
            >
              <ChevronsRight className="h-4 w-4" />
            </Button>
          </div>
        </div>
      )}
    </div>
  );
}
