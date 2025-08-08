<script setup lang="ts">
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import InputError from '@/components/input-error.vue';
import { Button } from '@/components/ui/button';
import type { SharedData } from '@/types';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { LoaderCircleIcon } from 'lucide-vue-next';
import FormSuccessful from '@/components/form-successful.vue';

const page = usePage<
  SharedData & {
    must_verify_email: boolean;
  }
>();

const form = useForm<{
  name: string;
  email: string;
}>({
  name: page.props.auth.user.name,
  email: page.props.auth.user.email,
});

const submit = (e: Event) => {
  e.preventDefault();

  form.put(`/user/profile-information`, {
    preserveScroll: true,
    errorBag: 'updateProfileInformation',
  });
};
</script>

<template>
  <Card>
    <CardHeader>
      <CardTitle>Profile information</CardTitle>
      <CardDescription>Update your profile information and email address.</CardDescription>
    </CardHeader>
    <CardContent>
      <form id="update-profile-form" @submit="submit">
        <div class="grid gap-6">
          <div class="grid gap-2">
            <Label htmlFor="name">Name</Label>
            <Input id="name" v-model="form.name" placeholder="Full name" />
            <InputError :message="form.errors.name" />
          </div>
          <div class="grid gap-2">
            <Label htmlFor="email">Email address</Label>
            <Input id="email" type="email" value="{form.data.email}" v-model="form.email" placeholder="Email address" />
            <InputError :message="form.errors.email" />
          </div>
          <div v-if="page.props.must_verify_email && page.props.auth.user.email_verified_at === null" class="text-sm">
            <p class="text-muted-foreground -mt-4 text-sm">
              Your email address is unverified.{' '}
              <Link href="/email/verification-notification" method="post" as="button" class="text-foreground underline">
                Click here to resend the verification email.
              </Link>
            </p>
            <div v-if="page.props.status === 'verification-link-sent'" class="text-success mt-2 text-sm font-medium">
              A new verification link has been sent to your email address.
            </div>
          </div>
        </div>
      </form>
    </CardContent>
    <CardFooter class="gap-2">
      <Button form="update-profile-form" :disabled="form.processing">
        <LoaderCircleIcon v-if="form.processing" class="animate-spin" />
        <FormSuccessful :successful="form.recentlySuccessful" />
        Save
      </Button>
    </CardFooter>
  </Card>
</template>
