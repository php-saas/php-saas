<script setup lang="ts">
import { Project } from '@/types/project';
import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogDescription,
  DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/input-error.vue';
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { LoaderCircleIcon } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
  project: Project;
}>();

const form = useForm({
  name: '',
});

const dialog = ref(false);

const submit = (e: Event) => {
  e.preventDefault();
  form.delete(`/settings/projects/${props.project.id}`, {
    onSuccess: () => {
      dialog.value = false;
    },
  });
};
</script>

<template>
  <Dialog v-model:open="dialog">
    <DialogTrigger as-child>
      <slot />
    </DialogTrigger>
    <DialogContent>
      <DialogHeader>
        <DialogTitle>Delete {{ props.project.name }}</DialogTitle>
        <DialogDescription>Delete project and all its resources.</DialogDescription>
      </DialogHeader>

      <form id="delete-project-form" @submit.prevent="{ submit }">
        <div class="grid gap-6">
          <div class="grid gap-2">
            <Label htmlFor="project-name">Name</Label>
            <Input id="project-name" v-model="form.name" />
            <InputError :message="form.errors.name" />
          </div>
        </div>
      </form>

      <DialogFooter className="gap-2 flex items-center justify-end">
        <DialogClose as-child>
          <Button variant="outline">Cancel</Button>
        </DialogClose>
        <Button form="delete-project-form" type="submit" variant="destructive" :disabled="form.processing" @click="submit">
          <LoaderCircleIcon v-if="form.processing" className="size-4 animate-spin" />
          Delete Project
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
