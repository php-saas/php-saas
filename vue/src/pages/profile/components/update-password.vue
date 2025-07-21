<script lang="ts" setup>
import { useForm } from '@inertiajs/vue3';
import InputError from '@/components/input-error.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { LoaderCircleIcon } from 'lucide-vue-next';
import FormSuccessful from '@/components/form-successful.vue';

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

function updatePassword() {
  form.put('/user/password', {
    preserveScroll: true,
    errorBag: 'updatePassword',
    onSuccess: () => form.reset(),
    onError: (errors) => {
      if (errors.password) {
        form.reset('password', 'password_confirmation');
      }
      if (errors.current_password) {
        form.reset('current_password');
      }
    },
  });
}
</script>

<template>
  <Card>
    <CardHeader>
      <CardTitle>Update password</CardTitle>
      <CardDescription> Ensure your account is using a long, random password to stay secure.</CardDescription>
    </CardHeader>
    <CardContent>
      <form id="update-password-form" @submit.prevent="updatePassword">
        <div class="grid gap-6">
          <div class="grid gap-2">
            <Label for="current_password">Current password</Label>
            <Input
              id="current_password"
              v-model="form.current_password"
              type="password"
              class="mt-1 block w-full"
              autocomplete="current-password"
              placeholder="Current password"
            />
            <InputError :message="form.errors.current_password" />
          </div>

          <div class="grid gap-2">
            <Label for="password">New password</Label>
            <Input
              id="password"
              v-model="form.password"
              type="password"
              class="mt-1 block w-full"
              autocomplete="new-password"
              placeholder="New password"
            />
            <InputError :message="form.errors.password" />
          </div>

          <div class="grid gap-2">
            <Label for="password_confirmation">Confirm password</Label>
            <Input
              id="password_confirmation"
              v-model="form.password_confirmation"
              type="password"
              class="mt-1 block w-full"
              autocomplete="new-password"
              placeholder="Confirm password"
            />
            <InputError :message="form.errors.password_confirmation" />
          </div>
        </div>
      </form>
    </CardContent>
    <CardFooter class="gap-2">
      <Button form="update-password-form" :disabled="form.processing">
        <LoaderCircleIcon v-if="form.processing" class="animate-spin" />
        <FormSuccessful :successful="form.recentlySuccessful" />
        Save password
      </Button>
    </CardFooter>
  </Card>
</template>
