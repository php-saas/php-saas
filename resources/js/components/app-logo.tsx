import AppLogoIcon from './app-logo-icon';
import { cn } from '@/lib/utils';

export default function AppLogo({ className = '' }: { className?: string }) {
  return (
    <>
      <div className={cn('flex aspect-square size-8 items-center justify-center rounded-md', className)}>
        <AppLogoIcon />
      </div>
    </>
  );
}
