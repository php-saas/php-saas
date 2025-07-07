import { Appearance, useAppearance } from '@/hooks/use-appearance';
import { cn } from '@/lib/utils';
import { LucideIcon, Monitor, Moon, Sun } from 'lucide-react';
import { HTMLAttributes } from 'react';

export default function AppearanceToggleTab({ className = '', ...props }: HTMLAttributes<HTMLDivElement>) {
  const { appearance, updateAppearance } = useAppearance();

  const tabs: { value: Appearance; icon: LucideIcon; label: string }[] = [
    { value: 'light', icon: Sun, label: 'Light' },
    { value: 'dark', icon: Moon, label: 'Dark' },
    { value: 'system', icon: Monitor, label: 'System' },
  ];

  return (
    <div className={cn('bg-input/30 flex w-full items-center justify-between gap-1 rounded-lg p-1', className)} {...props}>
      {tabs.map(({ value, icon: Icon }) => (
        <button
          key={value}
          onClick={() => updateAppearance(value)}
          className={cn(
            'flex w-full items-center justify-center rounded-md px-3.5 py-1.5 transition-colors',
            appearance === value ? 'bg-input/70 shadow-xs' : 'hover:bg-input/70',
          )}
        >
          <Icon className="-ml-1 h-4 w-4" />
        </button>
      ))}
    </div>
  );
}
