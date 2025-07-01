import { Head, useForm, usePage } from '@inertiajs/react';
import { LoaderCircleIcon } from 'lucide-react';
import { FormEvent } from 'react';

import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import Layout from '@/layouts/auth';
import { SharedData } from '@/types';
import { Alert, AlertDescription } from '@/components/ui/alert';

export default function VerifyEmail() {
  const page = usePage<SharedData>();
  const form = useForm({});

  const submit = (e: FormEvent) => {
    e.preventDefault();

    form.post(route('verification.send'));
  };

  return (
    <Layout title="Verify email" description="Please verify your email address by clicking on the link we just emailed to you.">
      <Head title="Email verification" />

      {page.props.flash?.status === 'verification-link-sent' && (
        <Alert>
          <AlertDescription>
            <p>A new verification link has been sent to the email address you provided during registration.</p>
          </AlertDescription>
        </Alert>
      )}

      <form onSubmit={submit} className="space-y-6 text-center">
        <Button disabled={form.processing} variant="secondary">
          {form.processing && <LoaderCircleIcon className="animate-spin" />}
          Resend verification email
        </Button>

        <TextLink href={route('logout')} method="post" className="mx-auto block text-sm">
          Log out
        </TextLink>
      </form>
    </Layout>
  );
}
