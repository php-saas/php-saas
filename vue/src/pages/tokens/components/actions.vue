<script setup lang="ts">
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Button } from '@/components/ui/button';
import { MoreVerticalIcon } from 'lucide-vue-next';
import { Token } from '@/types/token';
import DeleteToken from '@/pages/tokens/components/delete-token.vue';

const props = defineProps<{
  token: Token;
}>();
</script>

<template>
  <DropdownMenu modal>
    <DropdownMenuTrigger as-child>
      <Button variant="ghost" class="size-8">
        <MoreVerticalIcon />
      </Button>
    </DropdownMenuTrigger>
    <DropdownMenuContent align="end">
      <DeleteToken :token="props.token">
        <DropdownMenuItem variant="destructive" @select="(e) => e.preventDefault()">Delete</DropdownMenuItem>
      </DeleteToken>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
