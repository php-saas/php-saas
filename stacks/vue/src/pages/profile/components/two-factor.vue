<script lang="ts" setup>
import { ref, computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { CheckCircle2Icon, LoaderCircleIcon, XCircleIcon } from 'lucide-vue-next';
import InputError from '@/components/input-error.vue';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
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
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Alert, AlertDescription } from '@/components/ui/alert';

const page = usePage<{
  two_factor_enabled: boolean;
  two_factor_recovery_codes: string[];
  two_factor_confirmed_at: string | null;
  two_factor_must_confirm: boolean;
}>();

const isEnabled = computed(() => {
  if (page.props.two_factor_must_confirm) {
    return page.props.two_factor_enabled && page.props.two_factor_confirmed_at !== null;
  }
  return page.props.two_factor_enabled;
});

const enableOpen = ref(false);
const enableForm = useForm({});
const confirmForm = useForm({ code: '' });
const qrCode = ref('');
const qrCodeUrl = ref('');

function enableSubmit() {
  enableForm.post('/user/two-factor-authentication', {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      axios.get('/user/two-factor-qr-code').then((response) => {
        qrCode.value = response.data.svg;
        qrCodeUrl.value = response.data.url;
      });
      enableOpen.value = true;
    },
  });
}

function confirmSubmit() {
  confirmForm.post('/user/confirmed-two-factor-authentication', {
    preserveScroll: true,
    errorBag: 'confirmTwoFactorAuthentication',
    onSuccess: () => (enableOpen.value = false),
  });
}

const disableOpen = ref(false);
const disableForm = useForm({});
function disableSubmit() {
  disableForm.delete('/user/two-factor-authentication', {
    preserveScroll: true,
    onSuccess: () => (disableOpen.value = false),
  });
}

const regenOpen = ref(false);
const regenForm = useForm({});
function regenSubmit() {
  regenForm.post('/user/two-factor-recovery-codes', {
    preserveScroll: true,
    onSuccess: () => (regenOpen.value = false),
  });
}

const twoFactorRecoveryCodes = computed(() => {
  return page.props.two_factor_recovery_codes.length ? page.props.two_factor_recovery_codes.join('\n') : '';
});
</script>

<template>
  <Card>
    <CardHeader>
      <CardTitle>Two factor authentication</CardTitle>
      <CardDescription>Enable or Disable two factor authentication</CardDescription>
    </CardHeader>
    <CardContent>
      <Alert v-if="isEnabled">
        <AlertDescription>
          <div class="flex items-center gap-2">
            <CheckCircle2Icon class="text-success size-4" />
            <p>Two factor authentication is enabled</p>
          </div>
        </AlertDescription>
      </Alert>
      <Alert v-else>
        <AlertDescription>
          <div class="flex items-center gap-2">
            <XCircleIcon class="text-danger size-4" />
            Two factor authentication is <strong>not</strong> enabled
          </div>
        </AlertDescription>
      </Alert>

      <div v-if="isEnabled && page.props.two_factor_recovery_codes.length" class="mt-6 grid gap-6">
        <div class="grid gap-2">
          <Label for="recovery-codes">Recovery Codes</Label>
          <Textarea id="recovery-codes" v-model="twoFactorRecoveryCodes" disabled rows="5" />
        </div>
      </div>
    </CardContent>
    <CardFooter class="gap-2">
      <Button v-if="!isEnabled" @click="enableSubmit" :disabled="enableForm.processing">
        <LoaderCircleIcon v-if="enableForm.processing" class="animate-spin" />
        Enable Two Factor
      </Button>

      <Dialog v-model:open="enableOpen">
        <DialogContent class="sm:max-w-md">
          <DialogHeader>
            <DialogTitle>Enable Two Factor</DialogTitle>
            <DialogDescription>Enabling two factor authentication</DialogDescription>
          </DialogHeader>
          <form id="confirm-form" @submit.prevent="confirmSubmit">
            <div class="grid gap-6">
              <div class="grid gap-2">
                <Label for="qr-code">Scan this QR code with your authenticator app</Label>
                <div class="my-3 flex max-h-[400px] items-center justify-center">
                  <div v-html="qrCode"></div>
                </div>
              </div>
              <div class="grid gap-2">
                <Label for="qr-code-url">QR Code URL</Label>
                <Input id="qr-code-url" :value="qrCodeUrl" disabled />
              </div>
              <div v-if="page.props.two_factor_must_confirm" class="grid gap-2">
                <Label for="code">Confirmation Code</Label>
                <Input id="code" type="text" name="code" placeholder="Enter the confirmation code" autofocus v-model="confirmForm.code" />
                <InputError :message="confirmForm.errors.code" />
              </div>
            </div>
          </form>
          <DialogFooter>
            <DialogClose as-child>
              <Button variant="outline">Close</Button>
            </DialogClose>
            <Button v-if="page.props.two_factor_must_confirm" form="confirm-form" :disabled="confirmForm.processing">
              <LoaderCircleIcon v-if="confirmForm.processing" class="animate-spin" />
              Confirm
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      <Dialog v-model:open="regenOpen" v-if="isEnabled">
        <DialogTrigger as-child>
          <Button>Regenerate recovery codes</Button>
        </DialogTrigger>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Regenerate recovery codes</DialogTitle>
            <DialogDescription>Regenerate recovery codes</DialogDescription>
          </DialogHeader>
          <p>Are you sure you want to regenerate the recovery codes?</p>
          <DialogFooter>
            <DialogClose as-child>
              <Button variant="outline">Cancel</Button>
            </DialogClose>
            <Button @click="regenSubmit" :disabled="regenForm.processing">
              <LoaderCircleIcon v-if="regenForm.processing" class="animate-spin" />
              Regenerate
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      <Dialog v-model:open="disableOpen" v-if="isEnabled">
        <DialogTrigger as-child>
          <Button variant="destructive">Disable Two Factor</Button>
        </DialogTrigger>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Disable two factor</DialogTitle>
            <DialogDescription>Disable two factor</DialogDescription>
          </DialogHeader>
          <p>Are you sure you want to disable two factor authentication?</p>
          <DialogFooter>
            <DialogClose as-child>
              <Button variant="outline">Cancel</Button>
            </DialogClose>
            <Button @click="disableSubmit" variant="destructive" :disabled="disableForm.processing">
              <LoaderCircleIcon v-if="disableForm.processing" class="animate-spin" />
              Disable
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </CardFooter>
  </Card>
</template>
