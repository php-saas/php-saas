import AppLogoIcon from '@/components/app-logo-icon';
import { type PropsWithChildren } from 'react';

interface AuthLayoutProps {
  title?: string;
  description?: string;
}

export default function AuthSplitLayout({ children, title, description }: PropsWithChildren<AuthLayoutProps>) {
  return (
    <div className="relative mx-auto flex h-dvh max-w-sm flex-col items-center justify-center px-8 sm:px-0 lg:grid lg:max-w-none lg:grid-cols-2 lg:px-0">
      <div className="bg-muted text-foreground relative hidden h-full flex-col border-r p-10 lg:flex">
        <div className="bg-muted absolute inset-0" />
        <a href={route('home')} className="relative z-50 flex items-center text-lg font-medium">
          <AppLogoIcon className="mr-2 size-8" />
          PHP-SaaS
        </a>
        <div className="relative z-20 mt-auto">
          <blockquote className="space-y-2">
            <p className="text-foreground text-lg">&ldquo;If you’re building a lot of software, you need a good starter kit.&rdquo;</p>
            <footer className="text-muted-foreground text-sm">Saeed Vaziry</footer>
          </blockquote>
        </div>
      </div>
      <div className="w-full lg:p-8">
        <div className="mx-auto flex w-full flex-col justify-center space-y-6 lg:w-[350px]">
          <a href={route('home')} className="relative z-20 flex items-center justify-center lg:hidden">
            <AppLogoIcon className="size-10" />
          </a>
          <div className="flex flex-col items-center gap-2 text-center">
            <h1 className="text-xl font-medium">{title}</h1>
            <p className="text-muted-foreground text-sm text-balance">{description}</p>
          </div>
          {children}
        </div>
      </div>
    </div>
  );
}
