<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { Toaster } from '@/components/ui/sonner';
import { computed, h, watch } from 'vue';
import { toast } from 'vue-sonner';
import 'vue-sonner/style.css';
import { CheckCircle2Icon, CircleXIcon, TriangleAlertIcon, InfoIcon } from 'lucide-vue-next';

const page = usePage<{
  flash?: {
    success?: string;
    error?: string;
    warning?: string;
    info?: string;
  };
}>();

const flash = computed(() => page.props.flash);

watch(
  flash,
  (newFlash) => {
    if (newFlash?.success) {
      toast(h('div', { class: 'flex items-center gap-2' }, [h(CheckCircle2Icon, { class: 'text-success size-5' }), newFlash.success]));
    }
    if (newFlash?.error) {
      toast(h('div', { class: 'flex items-center gap-2' }, [h(CircleXIcon, { class: 'text-destructive size-5' }), newFlash.error]));
    }
    if (newFlash?.warning) {
      toast(h('div', { class: 'flex items-center gap-2' }, [h(TriangleAlertIcon, { class: 'text-warning size-5' }), newFlash.warning]));
    }
    if (newFlash?.info) {
      toast(h('div', { class: 'flex items-center gap-2' }, [h(InfoIcon, { class: 'text-info size-5' }), newFlash.info]));
    }
  },
  { immediate: true },
);
</script>

<template>
  <Toaster rich-colors position="bottom-center" />
</template>
