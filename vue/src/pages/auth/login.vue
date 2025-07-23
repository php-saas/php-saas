<script setup lang="ts">
import InputError from "@/components/input-error.vue";
import TextLink from "@/components/text-link.vue";
import { Button } from "@/components/ui/button";
import { Checkbox } from "@/components/ui/checkbox";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Head, useForm } from "@inertiajs/vue3";
import { LoaderCircle } from "lucide-vue-next";
import { ref } from "vue";
import { GitHubIcon } from "vue3-simple-icons";
import AuthLayout from "@/layouts/auth-layout.vue";

const form = useForm({
  email: "",
  password: "",
  remember: false,
});

const submit = () => {
  form.post("/login", {
    onFinish: () => form.reset("password"),
  });
};

const socialLoginLoading = ref("");
</script>

<template>
  <AuthLayout
    title="Log in to your account"
    description="Use your social account to log in."
  >
    <Head title="Log in" />

    <div class="space-y-2">
      <a href="/auth/github/redirect" @click="socialLoginLoading = 'github'">
        <Button
          variant="outline"
          class="w-full"
          :disabled="socialLoginLoading === 'github'"
        >
          <LoaderCircle
            v-if="socialLoginLoading === 'github'"
            class="animate-spin"
          />
          <GitHubIcon v-else />
          Continue with Github
        </Button>
      </a>
    </div>

    <p class="text-muted-foreground text-center text-sm">
      Or, use your email and password
    </p>

    <form @submit.prevent="submit">
      <div class="grid gap-6">
        <div class="grid gap-2">
          <Label for="email">Email address</Label>
          <Input
            id="email"
            type="email"
            required
            autofocus
            :tabindex="1"
            autocomplete="email"
            v-model="form.email"
            placeholder="email@example.com"
          />
          <InputError :message="form.errors.email" />
        </div>

        <div class="grid gap-2">
          <div class="flex items-center justify-between">
            <Label for="password">Password</Label>
            <TextLink href="/forgot-password" class="text-sm" :tabindex="5">
              Forgot password?</TextLink
            >
          </div>
          <Input
            id="password"
            type="password"
            required
            :tabindex="2"
            autocomplete="current-password"
            v-model="form.password"
            placeholder="Password"
          />
          <InputError :message="form.errors.password" />
        </div>

        <div class="flex items-center gap-2">
          <Label for="remember">
            <Checkbox id="remember" v-model="form.remember" :tabindex="3" />
            <span>Remember me</span>
          </Label>
        </div>

        <Button
          type="submit"
          class="mt-4 w-full"
          :tabindex="4"
          :disabled="form.processing"
        >
          <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
          Log in
        </Button>
      </div>

      <div class="text-muted-foreground mt-4 text-center text-sm">
        Don't have an account?
        <TextLink href="/register" :tabindex="5">Sign up</TextLink>
      </div>
    </form>
  </AuthLayout>
</template>
