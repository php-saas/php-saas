<script setup lang="ts">
import { ref, watch, defineProps, defineEmits } from 'vue';
import { useForm } from '@inertiajs/vue3';
import {
  Dialog,
  DialogTrigger,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogDescription,
  DialogFooter,
  DialogClose,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import InputError from '@/components/input-error.vue';
import { Label } from '@/components/ui/label';
import { LoaderCircle } from 'lucide-vue-next';
import type { Project } from '@/types/project';

const props = defineProps<{
  project?: Project;
  defaultOpen?: boolean;
}>();

const emit = defineEmits<{
  (e: 'update:open', value: boolean): void;
}>();

const open = ref(props.defaultOpen ?? false);

watch(() => props.defaultOpen, (val) => {
  if (val !== undefined) open.value = val;
});

function handleOpenChange(value: boolean) {
  open.value = value;
  emit('update:open', value);
}

const form = useForm({
  name: props.project?.name ?? '',
});

function submit(e?: Event) {
  if (e) e.preventDefault();

  if (props.project) {
    form.put(route('projects.update', props.project.id), {
      onSuccess: () => (open.value = false),
    });
  } else {
    form.post(route('projects.store'), {
      onSuccess: () => (open.value = false),
    });
  }
}
</script>

<template>
  <Dialog :open="open" @update:open="handleOpenChange">
    <DialogTrigger as-child>
      <slot />
    </DialogTrigger>

    <DialogContent class="sm:max-w-md">
      <DialogHeader>
        <DialogTitle>{{ props.project ? 'Edit Project' : 'Create Project' }}</DialogTitle>
        <DialogDescription>
          {{ props.project ? 'Edit the project details.' : 'Here you can create a new project.' }}
        </DialogDescription>
      </DialogHeader>

      <form id="project-form" @submit="submit">
        <div class="grid gap-6">
          <div class="grid gap-2">
            <Label for="name">Name</Label>
            <Input
              id="name"
              type="text"
              v-model="form.data.name"
              name="name"
            />
            <InputError :message="form.errors.name" />
          </div>
        </div>
      </form>

      <DialogFooter>
        <DialogClose as-child>
          <Button type="button" variant="outline">Cancel</Button>
        </DialogClose>
        <Button
          form="project-form"
          type="button"
          :disabled="form.processing"
          @click="submit"
        >
          <LoaderCircle v-if="form.processing" class="animate-spin mr-2" />
          Save
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
