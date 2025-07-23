<script setup lang="ts">
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Button } from '@/components/ui/button';
import { MoreVerticalIcon } from 'lucide-vue-next';
import { Project } from '@/types/project';
import DeleteProject from '@/pages/projects/components/delete-project.vue';
import Users from '@/pages/projects/components/users.vue';
import ProjectForm from '@/pages/projects/components/project-form.vue';
import LeaveProject from '@/pages/projects/components/leave-project.vue';

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
      <Users :project="project" v-if="project.role === 'owner'">
        <DropdownMenuItem @select="(e) => e.preventDefault()">Users</DropdownMenuItem>
      </Users>
      <ProjectForm :project="project" v-if="['owner', 'admin'].includes(project.role)">
        <DropdownMenuItem @select="(e) => e.preventDefault()">Edit</DropdownMenuItem>
      </ProjectForm>
      <LeaveProject :project="project" v-if="project.role !== 'owner'">
        <DropdownMenuItem @select="(e) => e.preventDefault()">Leave Project</DropdownMenuItem>
      </LeaveProject>
      <template v-if="project.role === 'owner'">
        <DropdownMenuSeparator />
        <DeleteProject :project="props.project">
          <DropdownMenuItem variant="destructive" @select="(e) => e.preventDefault()">Delete</DropdownMenuItem>
        </DeleteProject>
      </template>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
