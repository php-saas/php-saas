<script lang="ts" setup>
import { ref, watch, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import { RefreshCwIcon, ChevronDownIcon } from 'lucide-vue-next';

import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';

const polling = ref(false);
const poll = ref<{ start: () => void; stop: () => void } | undefined>(undefined);

const storedInterval = (localStorage.getItem('refresh_interval') as '5' | '10' | '30' | '60' | '0') || '10';
const refreshInterval = ref<0 | 5 | 10 | 30 | 60>(storedInterval === '0' ? 0 : (parseInt(storedInterval) as 5 | 10 | 30 | 60));

const intervalLabels: Record<0 | 5 | 10 | 30 | 60, string> = {
  5: '5s',
  10: '10s',
  30: '30s',
  60: '1m',
  0: 'OFF',
};

const refresh = () => {
  router.reload({
    onStart: () => (polling.value = true),
    onFinish: () => (polling.value = false),
  });
};

watch(refreshInterval, (newInterval) => {
  poll.value?.stop();

  if (newInterval > 0) {
    poll.value = router.poll(newInterval * 1000);
  } else {
    poll.value = undefined;
  }

  localStorage.setItem('refresh_interval', newInterval.toString());
});

onMounted(() => {
  if (refreshInterval.value > 0) {
    poll.value = router.poll(refreshInterval.value * 1000);
  }
});
</script>

<template>
  <div class="flex items-center">
    <Button variant="outline" size="sm" class="md:rounded-r-none" @click="refresh" :disabled="polling">
      <RefreshCwIcon :class="polling ? 'animate-spin' : ''" />
    </Button>
    <DropdownMenu>
      <DropdownMenuTrigger as-child>
        <Button variant="outline" size="sm" class="hidden rounded-l-none border-l-0 md:flex">
          {{ intervalLabels[refreshInterval] || 'Unknown' }}
          <ChevronDownIcon />
        </Button>
      </DropdownMenuTrigger>
      <DropdownMenuContent align="end">
        <DropdownMenuItem @select="() => (refreshInterval = 5)">5s</DropdownMenuItem>
        <DropdownMenuItem @select="() => (refreshInterval = 10)">10s</DropdownMenuItem>
        <DropdownMenuItem @select="() => (refreshInterval = 30)">30s</DropdownMenuItem>
        <DropdownMenuItem @select="() => (refreshInterval = 60)">1m</DropdownMenuItem>
        <DropdownMenuItem @select="() => (refreshInterval = 0)">OFF</DropdownMenuItem>
      </DropdownMenuContent>
    </DropdownMenu>
  </div>
</template>
