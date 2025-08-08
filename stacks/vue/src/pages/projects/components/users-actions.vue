<script setup lang="ts">
import { ProjectUser } from '@/types/project-user';
import RemoveUser from '@/pages/projects/components/remove-user.vue';
import { TrashIcon } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

const props = defineProps<{
  projectId: number;
  user: ProjectUser;
}>();
</script>

<template>
  <RemoveUser :user="props.user" :project-id="projectId">
    <Button variant="outline" class="size-7">
      <TrashIcon class="size-3"/>
    </Button>
  </RemoveUser>
</template>
