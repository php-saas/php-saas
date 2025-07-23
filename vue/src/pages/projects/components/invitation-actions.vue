<script setup lang="ts">
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Button } from '@/components/ui/button';
import Reject from '@/pages/projects/components/reject.vue';
import { ProjectUser } from '@/types/project-user';
import { MoreVerticalIcon } from 'lucide-vue-next';

const props = defineProps<{
  invitation: ProjectUser;
}>();

const accept = () => {
  window.location.href = `/projects/${props.invitation.project_id}/invitations/accept`;
};
</script>

<template>
  <div class="flex items-center justify-end">
    <DropdownMenu modal>
      <DropdownMenuTrigger as-childs>
        <Button variant="ghost" class="h-8 w-8 p-0">
          <span class="sr-only">Open menu</span>
          <MoreVerticalIcon />
        </Button>
      </DropdownMenuTrigger>
      <DropdownMenuContent align="end">
        <DropdownMenuItem @select="accept">Accept & Join</DropdownMenuItem>
        <DropdownMenuSeparator />
        <Reject :invitation="props.invitation">
          <DropdownMenuItem @select="(e) => e.preventDefault()" variant="destructive">Reject</DropdownMenuItem>
        </Reject>
      </DropdownMenuContent>
    </DropdownMenu>
  </div>
</template>
