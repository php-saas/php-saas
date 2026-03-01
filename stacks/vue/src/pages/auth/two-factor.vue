<script setup lang="ts">
import InputError from "@/components/input-error.vue";
import TextLink from "@/components/text-link.vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import AuthLayout from "@/layouts/auth-layout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { LoaderCircle } from "lucide-vue-next";

const form = useForm({
  code: "",
  recovery_code: "",
});

const submit = () => {
  form.post("/two-factor-challenge", {
    onFinish: () => form.reset(),
  });
};
</script>

<template>
  <AuthLayout
    title="Two factor challenge"
    description="Please enter the two-factor authentication code to continue."
  >
    <Head title="Two factor challenge" />

    <form @submit.prevent="submit">
      <div class="grid gap-6">
        <div class="grid gap-2">
          <Label for="code">Code</Label>
          <Input
            id="code"
            type="text"
            name="code"
            placeholder="Two factor code"
            v-model="form.code"
            autofocus
            :tabindex="1"
          />

          <InputError :message="form.errors.code" />
        </div>

        <div class="grid gap-2">
          <Label for="recovery_code">Recovery Code</Label>
          <Input
            id="recovery_code"
            type="text"
            name="recovery_code"
            placeholder="Or enter your recovery code"
            :tabindex="3"
            v-model="form.recovery_code"
          />

          <InputError :message="form.errors.recovery_code" />
        </div>

        <Button
          type="submit"
          class="mt-2 w-full"
          :tabindex="5"
          :disabled="form.processing"
        >
          <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
          Confirm
        </Button>
      </div>

      <div class="text-muted-foreground mt-4 text-center text-sm">
        <TextLink href="/login" :tabindex="6">Back to login</TextLink>
      </div>
    </form>
  </AuthLayout>
</template>
