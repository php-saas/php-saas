import { cn } from '@/lib/utils';
import { type BreadcrumbItem, type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { type ReactNode } from 'react';
import { BriefcaseIcon, UserIcon } from 'lucide-react';
import AppLayout from '@/layouts/app-layout';
import Container from '@/components/container';

const sidebarNavItems: NavItem[] = [
  {
    title: 'Profile',
    href: route('profile.index'),
    icon: UserIcon,
  },
  {
    title: 'Projects',
    href: route('projects.index'),
    icon: BriefcaseIcon,
  },
];

export default function SettingsLayout({ children, breadcrumbs }: { children: ReactNode; breadcrumbs?: BreadcrumbItem[] }) {
  if (typeof window === 'undefined') {
    return null;
  }

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Container className="max-w-5xl flex items-start flex-col lg:flex-row p-4 lg:gap-5">
        <div className="lg:w-48 space-y-5">
          <nav className="flex flex-row lg:flex-col gap-2">
            {sidebarNavItems.map((item) => (
              <Link
                key={item.title}
                href={item.href}
                className={cn(
                  'hover:bg-muted flex items-center gap-2 rounded-md px-2 py-1.5 text-sm font-medium',
                  item.onlyActivePath ? window.location.href === item.href : window.location.href.startsWith(item.href) ? 'bg-muted' : '',
                )}
              >
                {item.icon && <item.icon className="size-4" />}
                {item.title}
              </Link>
            ))}
          </nav>
        </div>
        <div className="flex-1 w-full">{children}</div>
      </Container>
    </AppLayout>
  );
}
