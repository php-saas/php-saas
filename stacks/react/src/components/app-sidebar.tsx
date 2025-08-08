import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { BookOpen, CreditCardIcon, Folder, LayoutGrid, SettingsIcon } from 'lucide-react';
import AppLogo from './app-logo';

const mainNavItems: NavItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
    icon: LayoutGrid,
  },
  {
    title: 'Settings',
    href: '/settings',
    icon: SettingsIcon,
  },
];

const footerNavItems: NavItem[] = [
  // <php-saas:billing>
  {
    title: 'Billing',
    href: '/billing',
    icon: CreditCardIcon,
  },
  // </php-saas:billing>
  {
    title: 'Repository',
    href: 'https://github.com/php-saas',
    icon: Folder,
    external: true,
  },
  {
    title: 'Documentation',
    href: 'https://php-saas.com',
    icon: BookOpen,
    external: true,
  },
];

export function AppSidebar() {
  return (
    <Sidebar collapsible="icon" variant="floating">
      <SidebarHeader>
        <SidebarMenu>
          <SidebarMenuItem>
            <SidebarMenuButton size="lg" asChild>
              <Link href="/dashboard" prefetch>
                <AppLogo />
              </Link>
            </SidebarMenuButton>
          </SidebarMenuItem>
        </SidebarMenu>
      </SidebarHeader>

      <SidebarContent>
        <NavMain items={mainNavItems} />
      </SidebarContent>

      <SidebarFooter>
        <NavFooter items={footerNavItems} className="mt-auto" />
        <NavUser />
      </SidebarFooter>
    </Sidebar>
  );
}
