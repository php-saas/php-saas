<script setup lang="ts">
import { computed, ref, VNodeRef } from 'vue';
import { SharedData } from '@/types';
import { useForm, usePage } from '@inertiajs/vue3';
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
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import InputError from '@/components/input-error.vue';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { ClipboardCheckIcon, ClipboardIcon, LoaderCircle } from 'lucide-vue-next';

const tokenInputRef = ref<VNodeRef | null>(null)
const copySuccess = ref(false);
const page = usePage<SharedData>();
const token = computed(() => {
  const flash = page.props.flash as { data?: { token?: string } };
  return flash.data?.token || '';
});
const dialog = ref(false);

const copyToClipboard = () => {
  (tokenInputRef.value as HTMLInputElement)?.select();
  navigator.clipboard.writeText(token.value || '').then(() => {
    copySuccess.value = true;
    setTimeout(() => {
      copySuccess.value = false;
    }, 2000);
  });
};

const form = useForm<
  Required<{
    name: string;
    ability: string;
  }>
>({
  name: '',
  ability: '',
});

const submit = (e: Event) => {
  e.preventDefault();
  form.post('/settings/tokens');
};
</script>

<template>
  <Dialog v-model:open="dialog">
    <DialogTrigger as-child>
      <slot />
    </DialogTrigger>
    <DialogContent class="max-h-screen overflow-y-auto sm:max-w-lg">
      <DialogHeader>
        <DialogTitle>Create an API token</DialogTitle>
        <DialogDescription>Create a new API token to use the API</DialogDescription>
      </DialogHeader>
      <form id="create-tag-form" @submit.prevent="submit">
        <div v-if="token" class="grid gap-6">
          <div class="grid gap-2">
            <Label htmlFor="token" class="flex items-center gap-1">
              <ClipboardCheckIcon v-if="copySuccess" class="text-success! size-4" />
              <ClipboardIcon v-else class="size-4" />
            </Label>
            <Input :ref="tokenInputRef" id="token" @click="copyToClipboard" type="text" v-model="token" class="cursor-pointer" />
          </div>
        </div>
        <div v-else class="grid gap-6">
          <div class="grid gap-2">
            <Label for="name">Name</Label>
            <Input type="text" id="name" name="name" v-model="form.name" />
            <InputError :message="form.errors.name" />
          </div>
          <div class="grid gap-2">
            <Label for="name">Name</Label>
            <Select name="ability" v-model="form.ability">
              <SelectTrigger class="w-full">
                <SelectValue placeholder="What can the token do?" />
              </SelectTrigger>
              <SelectContent>
                <SelectGroup>
                  <SelectItem key="ability-read" value="read"> read</SelectItem>
                  <SelectItem key="ability-write" value="write"> read & write</SelectItem>
                </SelectGroup>
              </SelectContent>
            </Select>
            <InputError :message="form.errors.ability" />
          </div>
        </div>
      </form>
      <DialogFooter v-if="!token">
        <DialogClose as-child>
          <Button type="button" variant="outline">Cancel</Button>
        </DialogClose>
        <Button type="button" @click="submit" :disabled="form.processing">
          <LoaderCircle v-if="form.processing" class="animate-spin" />
          Create
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
