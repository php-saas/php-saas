import { Head, useForm } from '@inertiajs/react';
import { LoaderCircleIcon } from 'lucide-react';
import { FormEvent } from 'react';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Alert, AlertDescription } from '@/components/ui/alert';
import AuthLayout from '@/layouts/auth-layout';
import InputError from '@/components/ui/input-error';

export default function Register() {
  const form = useForm<{
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
  }>({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
  });

  const submit = (e: FormEvent) => {
    e.preventDefault();
    form.post(route('register'), {
      onFinish: () => form.reset('password', 'password_confirmation'),
    });
  };

  return (
    <AuthLayout title="Create an account" description="Enter your details below to create your account">
      <Head title="Register" />
      <form id="register-form" onSubmit={submit}>
        <div className="grid gap-6">
          <div className="grid gap-2">
            <Label htmlFor="name">Name</Label>
            <Input
              id="name"
              type="text"
              required
              autoFocus
              tabIndex={1}
              autoComplete="name"
              value={form.data.name}
              onChange={(e) => form.setData('name', e.target.value)}
              placeholder="Full name"
            />
            <InputError message={form.errors.name} />
          </div>

          <div className="grid gap-2">
            <Label htmlFor="email">Email address</Label>
            <Input
              id="email"
              type="email"
              required
              tabIndex={2}
              autoComplete="email"
              value={form.data.email}
              onChange={(e) => form.setData('email', e.target.value)}
              placeholder="email@example.com"
            />
            <InputError message={form.errors.email} />
          </div>

          <div className="grid gap-2">
            <Label htmlFor="password">Password</Label>
            <Input
              id="password"
              type="password"
              required
              tabIndex={3}
              autoComplete="new-password"
              value={form.data.password}
              onChange={(e) => form.setData('password', e.target.value)}
              placeholder="Password"
            />
            <InputError message={form.errors.password} />
          </div>

          <div className="grid gap-2">
            <Label htmlFor="password_confirmation">Confirm password</Label>
            <Input
              id="password_confirmation"
              type="password"
              required
              tabIndex={4}
              autoComplete="new-password"
              value={form.data.password_confirmation}
              onChange={(e) => form.setData('password_confirmation', e.target.value)}
              placeholder="Confirm password"
            />
            <InputError message={form.errors.password_confirmation} />
          </div>

          <div className="grid gap-2">
            <Alert>
              <AlertDescription>
                <p className="space-x-1 text-sm">
                  <span>By creating an account, you agree to our</span>
                  <a href={route('terms')} className="text-primary" target="_blank">
                    terms of service
                  </a>
                  <span>and</span>
                  <a href={route('privacy')} className="text-primary" target="_blank">
                    privacy policy
                  </a>
                  .
                </p>
              </AlertDescription>
            </Alert>
          </div>

          <Button type="submit" className="mt-2 w-full" tabIndex={5} disabled={form.processing}>
            {form.processing && <LoaderCircleIcon className="animate-spin" />}
            Create account
          </Button>
        </div>

        <div className="text-muted-foreground mt-4 text-center text-sm">
          Already have an account?{' '}
          <TextLink href={route('login')} tabIndex={6}>
            Log in
          </TextLink>
        </div>
      </form>
    </AuthLayout>
  );
}
