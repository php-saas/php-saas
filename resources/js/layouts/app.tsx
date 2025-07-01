import { AppSidebar } from '@/components/app-sidebar';
import { AppHeader } from '@/components/app-header';
import { type BreadcrumbItem, NavItem } from '@/types';
import { type PropsWithChildren } from 'react';
import { SidebarInset, SidebarProvider } from '@/components/ui/sidebar';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import Toast from '@/components/toast';

export default function AppLayout({
  children,
  secondNavItems,
  secondNavTitle,
}: PropsWithChildren<{
  breadcrumbs?: BreadcrumbItem[];
  secondNavItems?: NavItem[];
  secondNavTitle?: string;
}>) {
  const queryClient = new QueryClient();

  return (
    <QueryClientProvider client={queryClient}>
      <SidebarProvider defaultOpen={!!(secondNavItems && secondNavItems.length > 0)}>
        <AppSidebar secondNavItems={secondNavItems} secondNavTitle={secondNavTitle} />
        <SidebarInset>
          <AppHeader />
          <div className="flex flex-1 flex-col">{children}</div>
          <Toast />
        </SidebarInset>
      </SidebarProvider>
    </QueryClientProvider>
  );
}
