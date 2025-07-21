<script setup lang="ts">
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Button } from '@/components/ui/button';
import { MoreVerticalIcon } from 'lucide-vue-next';
import { Project } from '@/types/project';
import DeleteProject from '@/pages/projects/components/delete-project.vue';

const props = defineProps<{
  project: Project;
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
      <DeleteProject :project="props.project">
        <DropdownMenuItem variant="destructive" @select="(e) => e.preventDefault()">Delete</DropdownMenuItem>
      </DeleteProject>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
