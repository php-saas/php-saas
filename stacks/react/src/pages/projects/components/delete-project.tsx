import { FormEvent, ReactNode, useState } from 'react';
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
import { Button } from '@/components/ui/button';
import { useForm } from '@inertiajs/react';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import InputError from '@/components/ui/input-error';
import { LoaderCircleIcon } from 'lucide-react';
import { Project } from '@/types/project';

export default function DeleteProject({ project, children }: { project: Project; children: ReactNode }) {
  const [open, setOpen] = useState(false);
  const form = useForm({
    name: '',
  });

  const submit = (e: FormEvent) => {
    e.preventDefault();
    form.delete(`/settings/projects/${project.id}`, {
      onSuccess: () => {
        setOpen(false);
      },
    });
  };

  return (
    <Dialog open={open} onOpenChange={setOpen}>
      <DialogTrigger asChild>{children}</DialogTrigger>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Delete {project.name}</DialogTitle>
          <DialogDescription>Delete project and all its resources.</DialogDescription>
        </DialogHeader>

        <form id="delete-project-form" onSubmit={submit}>
          <div className="grid gap-6">
            <div className="grid gap-2">
              <Label htmlFor="project-name">Name</Label>
              <Input id="project-name" value={form.data.name} onChange={(e) => form.setData('name', e.target.value)} />
              <InputError message={form.errors.name} />
            </div>
          </div>
        </form>

        <DialogFooter className="gap-2">
          <DialogClose asChild>
            <Button variant="outline">Cancel</Button>
          </DialogClose>

          <Button form="delete-project-form" variant="destructive" disabled={form.processing}>
            {form.processing && <LoaderCircleIcon className="size-4 animate-spin" />}
            Delete Project
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
