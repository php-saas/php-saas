import { Head, useForm } from '@inertiajs/react';
import { LoaderCircleIcon } from 'lucide-react';
import { FormEvent, useState } from 'react';
import InputError from '@/components/ui/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/auth-layout';
import { SiGithub } from '@icons-pack/react-simple-icons';

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
  const [socialLoginLoading, setSocialLoginLoading] = useState<string | null>(null);

  const submit = (e: FormEvent) => {
    e.preventDefault();
    form.post('/login', {
      onFinish: () => form.reset('password'),
    });
  };

  return (
    <AuthLayout title="Log in to your account" description="Use your social account to log in.">
      <Head title="Log in" />

      <div className="space-y-2">
        <a href="/auth/github/redirect" onClick={() => setSocialLoginLoading('github')}>
          <Button variant="outline" className="w-full" disabled={socialLoginLoading === 'github'}>
            {socialLoginLoading === 'github' ? <LoaderCircleIcon className="animate-spin" /> : <SiGithub />}
            Continue with Github
          </Button>
        </a>
      </div>

      <p className="text-muted-foreground text-center text-sm">Or, use your email and password</p>

      <form onSubmit={submit}>
        <div className="grid gap-6">
          <div className="grid gap-2">
            <Label htmlFor="email">Email address</Label>
            <Input
              id="email"
              type="email"
              required
              autoFocus
              tabIndex={1}
              autoComplete="email"
              value={form.data.email}
              onChange={(e) => form.setData('email', e.target.value)}
              placeholder="email@example.com"
            />
            <InputError message={form.errors.email} />
          </div>

          <div className="grid gap-2">
            <div className="flex items-center">
              <Label htmlFor="password">Password</Label>
              <TextLink href="/forgot-password" className="ml-auto text-sm" tabIndex={5}>
                Forgot password?
              </TextLink>
            </div>
            <Input
              id="password"
              type="password"
              required
              tabIndex={2}
              autoComplete="current-password"
              value={form.data.password}
              onChange={(e) => form.setData('password', e.target.value)}
              placeholder="Password"
            />
            <InputError message={form.errors.password} />
          </div>

          <div className="flex items-center gap-2">
            <Checkbox
              id="remember"
              name="remember"
              checked={form.data.remember}
              onClick={() => form.setData('remember', !form.data.remember)}
              tabIndex={3}
            />
            <Label htmlFor="remember">Remember me</Label>
          </div>

          <Button type="submit" className="mt-4 w-full" tabIndex={4} disabled={form.processing}>
            {form.processing && <LoaderCircleIcon className="animate-spin" />}
            Log in
          </Button>
        </div>

        <div className="text-muted-foreground mt-4 text-center text-sm">
          Don't have an account?{' '}
          <TextLink href="/register" tabIndex={5}>
            Sign up
          </TextLink>
        </div>
      </form>
    </AuthLayout>
  );
}
