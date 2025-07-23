<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ProjectUser } from '@/types/project-user';
import { ref } from 'vue';
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
import { LoaderCircleIcon } from 'lucide-vue-next';

const props = defineProps<{
  projectId: number;
  user: ProjectUser;
}>();

const dialog = ref(false);
const form = useForm({});

const submit = (e: Event) => {
  e.preventDefault();
  form.delete(
    `/settings/projects/${props.projectId}/users/${props.user.email}`,
    {
      onSuccess: () => {
        dialog.value = false;
      },
    },
  );
};
</script>

<template>
  <Dialog v-model:open="dialog">
    <DialogTrigger as-child>
      <slot />
    </DialogTrigger>
    <DialogContent>
      <DialogHeader>
        <DialogTitle>Remove user</DialogTitle>
        <DialogDescription>Remove user from project.</DialogDescription>
      </DialogHeader>

      <p>
        Are you sure you want to remove <b>{{ props.user.email }}</b> from this project?
      </p>

      <DialogFooter class="gap-2">
        <DialogClose as-child>
          <Button variant="outline">Cancel</Button>
        </DialogClose>
        <Button @click="submit" variant="destructive" :disabled="form.processing">
          <LoaderCircleIcon v-if="form.processing" class="animate-spin" />
          Remove
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
