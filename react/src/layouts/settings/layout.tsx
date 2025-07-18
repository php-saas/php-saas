import { cn } from '@/lib/utils';
import { type BreadcrumbItem, type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { type ReactNode } from 'react';
import { BriefcaseIcon, CommandIcon, UserIcon } from 'lucide-react';
import AppLayout from '@/layouts/app-layout';
import Container from '@/components/container';

const sidebarNavItems: NavItem[] = [
  {
    title: 'Profile',
    href: route('profile.index'),
    icon: UserIcon,
  },
  // <php-saas:projects>
  {
    title: 'Projects',
    href: route('projects.index'),
    icon: BriefcaseIcon,
  },
  // </php-saas:projects>
  // <php-saas:tokens>
  {
    title: 'API Tokens',
    href: route('tokens.index'),
    icon: CommandIcon,
  },
  // </php-saas:tokens>
];

export default function SettingsLayout({ children, breadcrumbs }: { children: ReactNode; breadcrumbs?: BreadcrumbItem[] }) {
  if (typeof window === 'undefined') {
    return null;
  }

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Container className="flex max-w-5xl flex-col items-start p-4 lg:flex-row lg:gap-5">
        <div className="space-y-5 lg:w-48">
          <nav className="flex flex-row gap-2 lg:flex-col">
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
        <div className="w-full flex-1">{children}</div>
      </Container>
    </AppLayout>
  );
}
