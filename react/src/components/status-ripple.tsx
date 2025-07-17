import * as React from 'react';
import { cva, type VariantProps } from 'class-variance-authority';

import { cn } from '@/lib/utils';

const variants = cva('', {
  variants: {
    variant: {
      default: 'bg-primary/90',
      success: 'bg-success/90',
      info: 'bg-info/90',
      warning: 'bg-warning/90',
      danger: 'bg-destructive/90',
      destructive: 'bg-destructive/90',
      gray: 'bg-gray/90',
      outline: 'bg-transparent border border-foreground/20 hover:bg-foreground/10',
    },
  },
  defaultVariants: {
    variant: 'default',
  },
});

function StatusRipple({ className, variant, ...props }: React.ComponentProps<'span'> & VariantProps<typeof variants>) {
  return (
    <span className={cn('relative flex size-3', className)} {...props}>
      <span className={cn('absolute inline-flex h-full w-full animate-ping rounded-full opacity-75', variants({ variant }))}></span>
      <span className={cn('relative inline-flex size-3 rounded-full', variants({ variant }))}></span>
    </span>
  );
}

export { StatusRipple, variants };
