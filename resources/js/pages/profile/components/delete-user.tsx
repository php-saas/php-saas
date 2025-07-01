import { useForm } from '@inertiajs/react';
import { FormEventHandler, useRef } from 'react';
import InputError from '@/components/ui/input-error';
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
import { Form, FormField, FormFields } from '@/components/ui/form';
import { LoaderCircleIcon } from 'lucide-react';

export default function DeleteUser() {
  const passwordInput = useRef<HTMLInputElement>(null);
  const form = useForm<{ password: string }>({ password: '' });

  const deleteUser: FormEventHandler = (e) => {
    e.preventDefault();

    form.delete(route('profile.destroy'), {
      preserveScroll: true,
      onSuccess: () => closeModal(),
      onError: () => passwordInput.current?.focus(),
      onFinish: () => form.reset(),
    });
  };

  const closeModal = () => {
    form.clearErrors();
    form.reset();
  };

  return (
    <Card className="border-destructive">
      <CardHeader>
        <CardTitle>Delete account</CardTitle>
        <CardDescription>Delete your account and all of its resources</CardDescription>
      </CardHeader>
      <CardContent className="space-y-2 p-4">
        <p>Please proceed with caution, this cannot be undone.</p>
        <Dialog>
          <DialogTrigger asChild>
            <Button variant="destructive">Delete account</Button>
          </DialogTrigger>
          <DialogContent>
            <DialogHeader>
              <DialogTitle>Delete account</DialogTitle>
              <DialogDescription className="sr-only">You're in the delete account form</DialogDescription>
            </DialogHeader>
            <Form id="delete-account-form" onSubmit={deleteUser} className="p-4">
              <FormFields>
                <FormField>
                  <p>Are you sure you want to delete your account?</p>
                  <p>
                    Once your account is deleted, all of its resources and data will also be permanently deleted. Please enter your password to
                    confirm you would like to permanently delete your account.
                  </p>
                </FormField>
                <FormField>
                  <Label htmlFor="password" className="sr-only">
                    Password
                  </Label>
                  <Input
                    id="password"
                    type="password"
                    name="password"
                    ref={passwordInput}
                    value={form.data.password}
                    onChange={(e) => form.setData('password', e.target.value)}
                    placeholder="Password"
                    autoComplete="current-password"
                  />
                  <InputError message={form.errors.password} />
                </FormField>
              </FormFields>
            </Form>
            <DialogFooter className="gap-2">
              <DialogClose asChild>
                <Button variant="outline" onClick={closeModal}>
                  Cancel
                </Button>
              </DialogClose>
              <Button form="delete-account-form" variant="destructive" disabled={form.processing}>
                {form.processing && <LoaderCircleIcon className="size-4 animate-spin" />}
                Delete account
              </Button>
            </DialogFooter>
          </DialogContent>
        </Dialog>
      </CardContent>
    </Card>
  );
}
