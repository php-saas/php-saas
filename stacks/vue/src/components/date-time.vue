<script setup lang="ts">
import { computed } from 'vue';
import moment from 'moment';

const props = defineProps<{
  date: string;
  format?: string;
  className?: string;
}>();

const formattedDate = computed(() => moment(props.date).format(props.format || 'YYYY-MM-DD hh:mm:ss'));
</script>

<template>
  <time :dateTime="date" :class="className">
    {{ formattedDate }}
  </time>
</template>
