<script lang="ts" setup>
import { CheckIcon } from 'lucide-vue-next'

defineProps<{
  successful: boolean
}>()
</script>

<template>
  <Transition
    enter="transition ease-in-out"
    enter-from="opacity-0"
    leave="transition ease-in-out"
    leave-to="opacity-0"
  >
    <CheckIcon v-if="successful" />
  </Transition>
</template>
