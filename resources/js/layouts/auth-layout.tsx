import AuthLayoutTemplate from '@/layouts/auth/auth-card-layout';
import Toast from '@/components/toast';
import { ReactNode } from 'react';

export default function AuthLayout({ children, title, description, ...props }: { children: ReactNode; title: string; description: string }) {
  return (
    <AuthLayoutTemplate title={title} description={description} {...props}>
      {children}
      <Toast />
    </AuthLayoutTemplate>
  );
}
