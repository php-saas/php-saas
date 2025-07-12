import AppLayout from '@/layouts/app-layout';
import { Head, usePage } from '@inertiajs/react';
import { Project } from '@/types/project';

export default function Show() {
  const page = usePage<{
    project: Project;
  }>();

  return (
    <AppLayout>
      <Head title={page.props.project.name} />
    </AppLayout>
  );
}
