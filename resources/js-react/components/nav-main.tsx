import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem, SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { ProjectSwitch } from '@/components/project-switch';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { ChevronsUpDownIcon } from 'lucide-react';
import { useInitials } from '@/hooks/use-initials';

export function NavMain({ items = [] }: { items: NavItem[] }) {
  const page = usePage<SharedData>();
  return (
    <SidebarGroup className="px-2 py-0">
      <SidebarGroupLabel>Platform</SidebarGroupLabel>
      <SidebarMenu>
        {/*<php-saas:projects>*/}
        <SidebarMenuItem>
          <ProjectSwitch>
            <SidebarMenuButton
              asChild
              tooltip={{ children: 'Project Switch' }}
              className="w-full group-data-[collapsible=icon]:flex group-data-[collapsible=icon]:items-center group-data-[collapsible=icon]:justify-center"
            >
              <div className="flex items-center justify-between px-1.5">
                <div className="flex items-center justify-center gap-1.5">
                  <Avatar className="size-5 rounded-xs group-data-[collapsible=icon]:size-8">
                    <AvatarFallback className="rounded-sm">
                      {useInitials()(page.props.project_provider.current?.name.replaceAll(' ', '') ?? '')}
                    </AvatarFallback>
                  </Avatar>
                  <span className="group-data-[collapsible=icon]:hidden">{page.props.project_provider.current?.name}</span>
                </div>
                <ChevronsUpDownIcon size={5} className="group-data-[collapsible=icon]:hidden" />
              </div>
            </SidebarMenuButton>
          </ProjectSwitch>
        </SidebarMenuItem>
        {/*</php-saas:projects>*/}
        {items.map((item) => (
          <SidebarMenuItem key={item.title}>
            <SidebarMenuButton asChild isActive={window.location.href.startsWith(item.href)} tooltip={{ children: item.title }}>
              {item.redirect ? (
                <a href={item.href} target={item.external ? '_blank' : '_self'}>
                  {item.icon && <item.icon />}
                  <span>{item.title}</span>
                </a>
              ) : (
                <Link href={item.href} prefetch>
                  {item.icon && <item.icon />}
                  <span>{item.title}</span>
                </Link>
              )}
            </SidebarMenuButton>
          </SidebarMenuItem>
        ))}
      </SidebarMenu>
    </SidebarGroup>
  );
}
