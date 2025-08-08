<script setup lang="ts">
import InputError from "@/components/input-error.vue";
import TextLink from "@/components/text-link.vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Head, useForm } from "@inertiajs/vue3";
import { LoaderCircle, CheckCircle2 } from "lucide-vue-next";
import AuthLayout from "@/layouts/auth-layout.vue";
import { Alert, AlertDescription } from "@/components/ui/alert";
import { SharedData } from "@/types";

const props = defineProps<SharedData>();

const form = useForm({
  email: "",
});

const submit = () => {
  form.post('/forgot-password');
};
</script>

<template>
  <AuthLayout
    title="Forgot password"
    description="Enter your email to receive a password reset link"
  >
    <Head title="Forgot password" />

    <Alert class="mb-4" v-if="props.flash?.status">
      <AlertDescription class="flex items-center gap-2">
        <CheckCircle2 class="text-success size-4" />
        {{ props.flash?.status }}
      </AlertDescription>
    </Alert>

    <div class="space-y-6">
      <form @submit.prevent="submit">
        <div class="grid gap-2">
          <Label for="email">Email address</Label>
          <Input
            id="email"
            type="email"
            required
            name="email"
            autocomplete="off"
            v-model="form.email"
            autofocus
            placeholder="email@example.com"
          />
          <InputError :message="form.errors.email" />
        </div>

        <div class="my-6 flex items-center justify-start">
          <Button class="w-full" :disabled="form.processing">
            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
            Email password reset link
          </Button>
        </div>
      </form>

      <div class="text-muted-foreground space-x-1 text-center text-sm">
        <span>Or, return to</span>
        <TextLink href="/login">log in</TextLink>
      </div>
    </div>
  </AuthLayout>
</template>
