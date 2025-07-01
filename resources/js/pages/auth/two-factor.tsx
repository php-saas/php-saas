import { Head, Link, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEvent } from 'react';
import InputError from '@/components/ui/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/auth';
import { Form, FormField, FormFields } from '@/components/ui/form';

export default function TwoFactor() {
  const form = useForm<{ code: string; recovery_code: string }>({
    code: '',
    recovery_code: '',
  });

  const submit = (e: FormEvent) => {
    e.preventDefault();

    form.post(route('two-factor.login.store'), {
      onFinish: () => form.reset(),
    });
  };

  return (
    <AuthLayout title="Two factor challenge" description="Please enter the two-factor authentication code to continue.">
      <Head title="Confirm password" />

      <Form onSubmit={submit}>
        <FormFields>
          <FormField>
            <Label htmlFor="code">Code</Label>
            <Input
              id="code"
              type="text"
              name="code"
              placeholder="Two factor code"
              value={form.data.code}
              autoFocus
              tabIndex={1}
              onChange={(e) => form.setData('code', e.target.value)}
            />

            <InputError message={form.errors.code} />
          </FormField>

          <FormField>
            <Label htmlFor="recovery_code">Recovery Code</Label>
            <Input
              id="recovery_code"
              type="text"
              name="recovery_code"
              placeholder="Or enter your recovery code"
              tabIndex={3}
              value={form.data.recovery_code}
              onChange={(e) => form.setData('recovery_code', e.target.value)}
            />

            <InputError message={form.errors.recovery_code} />
          </FormField>

          <div className="space-y-2">
            <Button tabIndex={2} className="w-full" disabled={form.processing}>
              {form.processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
              Confirm
            </Button>
            <Button tabIndex={4} variant="ghost" asChild>
              <Link className="block w-full" method="post" href={route('logout')}>
                Back to login
              </Link>
            </Button>
          </div>
        </FormFields>
      </Form>
    </AuthLayout>
  );
}
