<script setup lang="ts">
import { computed } from 'vue'
import { cn } from '@/lib/utils'

interface Props {
  class?: string
}

const props = defineProps<Props>()

const className = computed(() => cn('container mx-auto space-y-5', props.class))
</script>

<template>
  <div :class="className">
    <slot />
  </div>
</template>
