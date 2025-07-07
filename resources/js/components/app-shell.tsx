import { SidebarProvider } from '@/components/ui/sidebar';
import { ReactNode, useState } from 'react';

interface AppShellProps {
  children: ReactNode;
  variant?: 'header' | 'sidebar';
}

export function AppShell({ children, variant = 'header' }: AppShellProps) {
  const [open, setOpen] = useState((localStorage.getItem('sidebarOpen') || 'true') === 'true');

  if (variant === 'header') {
    return <div className="flex min-h-screen w-full flex-col">{children}</div>;
  }

  const sidebarOpenChanged = (value: boolean) => {
    localStorage.setItem('sidebarOpen', value ? 'true' : 'false');
    setOpen(value);
  };

  return (
    <SidebarProvider open={open} onOpenChange={sidebarOpenChanged}>
      {children}
    </SidebarProvider>
  );
}
