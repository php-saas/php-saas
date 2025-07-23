<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Project } from '@/types/project';
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
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import InputError from '@/components/input-error.vue';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Button } from '@/components/ui/button';
import { LoaderCircleIcon } from 'lucide-vue-next';
import { ref } from 'vue';

const dialog = ref(false);

const props = defineProps<{
  project: Project;
  onInviteSent?: () => void;
}>();

const form = useForm({
  email: '',
  role: 'viewer',
});

const submit = (e: Event) => {
  e.preventDefault();
  form.post(`/settings/projects/${props.project.id}/users`, {
    onSuccess: () => {
      dialog.value = false;
      if (props.onInviteSent) {
        props.onInviteSent();
      }
    },
  });
};
</script>

<template>
  <Dialog v-model:open="dialog">
    <DialogTrigger as-child>
      <slot />
    </DialogTrigger>
    <DialogContent class="sm:max-w-lg">
      <DialogHeader>
        <DialogTitle>Invite users to project</DialogTitle>
        <DialogDescription>Invite a new user to project</DialogDescription>
      </DialogHeader>
      <form id="invite-form" @sub.prevent="submit">
        <div class="grid gap-6">
          <div class="grid gap-2">
            <Label for="email">Email</Label>
            <Input id="email" name="email" type="email" v-model="form.email" />
            <InputError :message="form.errors.email" />
          </div>
          <div class="grid gap-2">
            <Label for="role">Role</Label>
            <Select :default-value="form.role" v-model="form.role">
              <SelectTrigger id="role" name="role" class="w-full">
                <SelectValue placeholder="Select a role" />
              </SelectTrigger>
              <SelectContent>
                <SelectGroup>
                  <SelectItem value="admin">Admin</SelectItem>
                  <SelectItem value="viewer">Viewer</SelectItem>
                </SelectGroup>
              </SelectContent>
            </Select>
            <InputError :message="form.errors.role" />
          </div>
        </div>
      </form>
      <DialogFooter>
        <DialogClose as-child>
          <Button variant="outline">Close</Button>
        </DialogClose>
        <Button form="invite-form" :disabled="form.processing" @click="submit">
          <LoaderCircleIcon v-if="form.processing" class="animate-spin" />
          Invite
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
