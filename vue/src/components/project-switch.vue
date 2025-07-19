<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import ProjectForm from '@/pages/projects/components/project-form.vue';
import { PlusIcon } from 'lucide-vue-next';
import { SharedData } from '@/types';
import {
  DropdownMenu,
  DropdownMenuCheckboxItem,
  DropdownMenuContent,
  DropdownMenuItem, DropdownMenuRadioGroup, DropdownMenuRadioItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger
} from '@/components/ui/dropdown-menu';

const page = usePage<SharedData>();
const form = useForm({});
const projects = computed(() => page.props.project_provider.list || []);
const currentProjectId = ref(page.props.project_provider.current?.id?.toString() || '');

function handleProjectChange(projectId: string) {
  const selectedProject = projects.value.find((p) => p.id.toString() === projectId);
  if (selectedProject) {
    currentProjectId.value = selectedProject.id.toString();
    form.put(`/settings/projects/${projectId}/switch?currentPath=${window.location.pathname}`);
  }
}
</script>

<template>
  <div class="flex items-center">
    <DropdownMenu :modal="false">
      <DropdownMenuTrigger as-child>
        <slot />
      </DropdownMenuTrigger>

      <DropdownMenuContent class="w-56" align="start">
        <DropdownMenuRadioGroup v-model="currentProjectId">
          <DropdownMenuRadioItem
            v-for="project in projects"
            :key="project.id.toString()"
            :value="project.id.toString()"
            @select="handleProjectChange(project.id.toString())"
          >
            {{ project.name }}
          </DropdownMenuRadioItem>
        </DropdownMenuRadioGroup>

        <DropdownMenuSeparator />

        <ProjectForm>
          <DropdownMenuItem class="gap-0" as-child @select.prevent>
            <div class="flex items-center">
              <PlusIcon :size="16" />
              <span class="ml-2">Create new project</span>
            </div>
          </DropdownMenuItem>
        </ProjectForm>
      </DropdownMenuContent>
    </DropdownMenu>
  </div>
</template>
