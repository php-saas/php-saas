import AppLogoIcon from '@/components/app-logo-icon';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { type PropsWithChildren } from 'react';

export default function AuthCardLayout({
  children,
  title,
  description,
}: PropsWithChildren<{
  name?: string;
  title?: string;
  description?: string;
}>) {
  return (
    <div className="bg-muted flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
      <div className="flex w-full max-w-md flex-col gap-6">
        <a href="/" className="flex items-center gap-2 self-center font-medium">
          <div className="flex h-9 w-9 items-center justify-center">
            <AppLogoIcon className="size-9" />
          </div>
        </a>

        <div className="flex flex-col gap-6">
          <Card className="rounded-xl">
            <CardHeader className="px-10 pt-8 text-center">
              <CardTitle className="text-xl">{title}</CardTitle>
              <CardDescription>{description}</CardDescription>
            </CardHeader>
            <CardContent className="space-y-4 px-10">{children}</CardContent>
          </Card>
        </div>
      </div>
    </div>
  );
}
