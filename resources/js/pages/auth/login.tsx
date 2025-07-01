import { Head, useForm } from '@inertiajs/react';
import { LoaderCircleIcon } from 'lucide-react';
import { FormEvent } from 'react';
import InputError from '@/components/ui/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/auth';
import { Form, FormField, FormFields } from '@/components/ui/form';

export default function Login() {
  const form = useForm<{
    email: string;
    password: string;
    remember: boolean;
  }>({
    email: '',
    password: '',
    remember: false,
  });

  const submit = (e: FormEvent) => {
    e.preventDefault();
    form.post(route('login.store'), {
      onFinish: () => form.reset('password'),
    });
  };

  return (
    <AuthLayout title="Log in to your account" description="Enter your email and password below to log in">
      <Head title="Log in" />

      <Form onSubmit={submit}>
        <FormFields>
          <FormField>
            <Label htmlFor="email">Email address</Label>
            <Input
              id="email"
              type="email"
              autoFocus
              tabIndex={1}
              autoComplete="email"
              value={form.data.email}
              onChange={(e) => form.setData('email', e.target.value)}
              placeholder="email@example.com"
            />
            <InputError message={form.errors.email} />
          </FormField>

          <FormField>
            <div className="flex items-center">
              <Label htmlFor="password">Password</Label>
              <TextLink href={route('password.request')} className="ml-auto text-sm" tabIndex={5}>
                Forgot password?
              </TextLink>
            </div>
            <Input
              id="password"
              type="password"
              tabIndex={2}
              autoComplete="current-password"
              value={form.data.password}
              onChange={(e) => form.setData('password', e.target.value)}
              placeholder="Password"
            />
            <InputError message={form.errors.password} />
          </FormField>

          <FormField className="flex items-center">
            <Checkbox
              id="remember"
              name="remember"
              checked={form.data.remember}
              onClick={() => form.setData('remember', !form.data.remember)}
              tabIndex={3}
            />
            <Label htmlFor="remember">Remember me</Label>
          </FormField>

          <Button type="submit" className="mt-4 w-full" tabIndex={4} disabled={form.processing}>
            {form.processing && <LoaderCircleIcon className="animate-spin" />}
            Log in
          </Button>
        </FormFields>

        <div className="text-muted-foreground text-center text-sm">
          Don't have an account?{' '}
          <TextLink href={route('register')} tabIndex={5}>
            Sign up
          </TextLink>
        </div>
      </Form>
    </AuthLayout>
  );
}
