<script setup lang="ts">
import InputError from '@/components/input-error.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { LoaderCircleIcon } from 'lucide-vue-next';
import { useForm } from '@inertiajs/vue3';

const form = useForm<{ password: string }>({ password: '' });

const deleteUser = (e: Event) => {
  e.preventDefault();

  form.delete(`/settings/profile`, {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onFinish: () => form.reset(),
  });
};

const closeModal = () => {
  form.clearErrors();
  form.reset();
};
</script>

<template>
  <Card class="border-destructive">
    <CardHeader>
      <CardTitle>Delete account</CardTitle>
      <CardDescription>Delete your account and all of its resources</CardDescription>
    </CardHeader>
    <CardContent class="space-y-2">
      <p>Please proceed with caution, this cannot be undone.</p>
      <Dialog>
        <DialogTrigger as-child>
          <Button variant="destructive">Delete account</Button>
        </DialogTrigger>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Delete account</DialogTitle>
            <DialogDescription>You're in the delete account form</DialogDescription>
          </DialogHeader>
          <form id="delete-account-form" @submit="deleteUser">
            <div class="grid gap-6">
              <div class="grid gap-2">
                <p>Are you sure you want to delete your account?</p>
                <p>
                  Once your account is deleted, all of its resources and data will also be permanently deleted. Please enter your password to confirm
                  you would like to permanently delete your account.
                </p>
              </div>
              <div class="grid gap-2">
                <Label htmlFor="password">Password</Label>
                <Input id="password" type="password" name="password" v-model="form.password" placeholder="Password" required />
                <InputError :message="form.errors.password" />
              </div>
            </div>
          </form>
          <DialogFooter class="gap-2">
            <DialogClose as-child>
              <Button variant="outline" @click="closeModal">Cancel</Button>
            </DialogClose>
            <Button form="delete-account-form" variant="destructive" :disabled="form.processing">
              <LoaderCircleIcon v-if="form.processing" class="animate-spin" />
              Delete account
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </CardContent>
  </Card>
</template>
