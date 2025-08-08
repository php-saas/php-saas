<script setup lang="ts">
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
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { LoaderCircleIcon } from 'lucide-vue-next';
import { ref } from 'vue';
import { Token } from '@/types/token';

const props = defineProps<{
  token: Token;
}>();

const form = useForm({});

const dialog = ref(false);

const submit = (e: Event) => {
  e.preventDefault();
  form.delete(`/settings/tokens/${props.token.id}`, {
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
        <DialogTitle>Delete {{ props.token.name }}</DialogTitle>
        <DialogDescription>Delete API Token</DialogDescription>
      </DialogHeader>

      <p>
        Are you sure you want to delete <strong>{{ props.token.name }}</strong
        >?
      </p>

      <DialogFooter class="flex items-center justify-end gap-2">
        <DialogClose as-child>
          <Button variant="outline">Cancel</Button>
        </DialogClose>
        <Button variant="destructive" :disabled="form.processing" @click="submit">
          <LoaderCircleIcon v-if="form.processing" class="size-4 animate-spin" />
          Delete token
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
