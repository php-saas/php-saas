<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Project } from '@/types/project';
import { ref } from 'vue';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
  DialogClose,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { LoaderCircleIcon } from 'lucide-vue-next';

const props = defineProps<{
  project: Project;
}>();
const form = useForm({});
const dialog = ref(false);

const submit = (e: Event) => {
  e.preventDefault();
  form.delete(`/settings/projects/${props.project.id}/leave`, {
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
        <DialogTitle>Leave {{ props.project.name }}</DialogTitle>
        <DialogDescription>Leave project {{ props.project.name }}</DialogDescription>
      </DialogHeader>

      <p>Are you sure you want to leave this project?</p>

      <DialogFooter class="gap-2">
        <DialogClose asChild>
          <Button variant="outline">Cancel</Button>
        </DialogClose>

        <Button @click="submit" variant="destructive" :disabled="form.processing">
          <LoaderCircleIcon v-if="form.processing" class="animate-spin" />
          Leave
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
