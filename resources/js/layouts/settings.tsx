import { type BreadcrumbItem, type NavItem } from '@/types';
import { ListIcon, UserIcon } from 'lucide-react';
import { ReactNode } from 'react';
import AppLayout from '@/layouts/app';

const sidebarNavItems: NavItem[] = [
  {
    title: 'Profile',
    href: route('profile.index'),
    icon: UserIcon,
  },
  {
    title: 'Projects',
    href: route('projects.index'),
    icon: ListIcon,
  },
];

export default function SettingsLayout({ children, breadcrumbs }: { children: ReactNode; breadcrumbs?: BreadcrumbItem[] }) {
  // When server-side rendering, we only render the layout on the client...
  if (typeof window === 'undefined') {
    return null;
  }

  return (
    <AppLayout breadcrumbs={breadcrumbs} secondNavItems={sidebarNavItems} secondNavTitle="Settings">
      {children}
    </AppLayout>
  );
}
