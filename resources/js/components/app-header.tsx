import { SidebarTrigger } from '@/components/ui/sidebar';
import { ProjectSwitch } from '@/components/project-switch';
import AppCommand from '@/components/app-command';
import Refresh from '@/components/refresh';

export function AppHeader() {
  return (
    <header className="bg-background -ml-1 flex h-12 shrink-0 items-center justify-between gap-2 border-b p-4 md:-ml-2">
      <div className="flex items-center">
        <SidebarTrigger className="-ml-1 md:hidden" />
        <div className="flex items-center space-x-2 text-xs">
          <ProjectSwitch />
        </div>
      </div>
      <div className="flex items-center gap-2">
        <AppCommand />
        <Refresh />
      </div>
    </header>
  );
}
