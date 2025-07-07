import { type SharedData } from '@/types';
import { useForm, usePage } from '@inertiajs/react';
import { ReactNode, useState } from 'react';
import {
  DropdownMenu,
  DropdownMenuCheckboxItem,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { PlusIcon } from 'lucide-react';
import ProjectForm from '@/pages/projects/components/project-form';

export function ProjectSwitch({ children }: { children: ReactNode }) {
  const page = usePage<SharedData>();
  const { auth } = page.props;
  const [selectedProject, setSelectedProject] = useState(auth.currentProject.id.toString());
  const form = useForm();

  const handleProjectChange = (projectId: string) => {
    const selectedProject = auth.projects.find((project) => project.id.toString() === projectId);
    if (selectedProject) {
      setSelectedProject(selectedProject.id.toString());
      form.put(route('projects.switch', { project: projectId, currentPath: window.location.pathname }));
    }
  };

  return (
    <div className="flex items-center">
      <DropdownMenu modal={false}>
        <DropdownMenuTrigger asChild>{children}</DropdownMenuTrigger>
        <DropdownMenuContent className="w-56" align="start">
          {auth.projects.map((project) => (
            <DropdownMenuCheckboxItem
              key={project.id.toString()}
              checked={selectedProject === project.id.toString()}
              onCheckedChange={() => handleProjectChange(project.id.toString())}
            >
              {project.name}
            </DropdownMenuCheckboxItem>
          ))}
          <DropdownMenuSeparator />
          <ProjectForm>
            <DropdownMenuItem className="gap-0" asChild onSelect={(e) => e.preventDefault()}>
              <div className="flex items-center">
                <PlusIcon size={5} />
                <span className="ml-2">Create new project</span>
              </div>
            </DropdownMenuItem>
          </ProjectForm>
        </DropdownMenuContent>
      </DropdownMenu>
    </div>
  );
}
