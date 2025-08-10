<?php

namespace PHPSaaS\PHPSaaS\Traits;

trait Projects
{
    protected array $projectsFilesToDelete = [
        'app/Enums/ProjectRole.php',
        'app/Http/Resources/ProjectResource.php',
        'app/Http/Resources/ProjectUserResource.php',
        'app/Mail/ProjectInvitation.php',
        'app/Models/Project.php',
        'app/Models/ProjectUser.php',
        'app/Policies/ProjectPolicy.php',
        'app/Providers/ProjectServiceProvider.php',
        'app/Traits/HasProjects.php',
        'database/factories/ProjectFactory.php',
        'database/migrations/2025_06_29_115509_create_projects_table.php',
        'database/migrations/2025_06_29_115510_create_project_user_table.php',
        'database/migrations/2025_07_12_212608_add_current_project_id_to_users_table.php',
        'resources/js/components/project-switch.tsx',
        'resources/js/components/project-switch.vue',
        'resources/js/types/project.d.ts',
        'resources/js/types/project-user.d.ts',
        'resources/emails/project-invitation.blade.php',
        'tests/Unit/Mail/ProjectInvitationTest.php',
    ];

    protected array $projectsDirectoriesToDelete = [
        'app/Actions/Project',
        'app/Http/Controllers/Project',
        'tests/Feature/Project',
        'resources/js/pages/projects',
    ];

    protected function setupProjects(): void
    {
        if ($this->projects === 'projects') {
            return;
        }

        if ($this->projects === 'none') {
            $this->removeBlocks($this->path, 'projects');

            foreach ($this->projectsFilesToDelete as $file) {
                $this->fileSystem->delete($this->path.'/'.$file);
            }

            foreach ($this->projectsDirectoriesToDelete as $directory) {
                $this->fileSystem->deleteDirectory($this->path.'/'.$directory);
            }

            return;
        }

        $name = str($this->projects)->lower()->singular()->toString();

        rename_directories_with_name($this->path, get_directories_in_path($this->path), 'project', $name);
        rename_directories_with_name($this->path, get_directories_in_path($this->path), 'Project', ucfirst($name));

        $excludedFiles = [
            'composer.json',
            'composer.lock',
            'package.json',
            'package-lock.json',
        ];
        rename_files_with_name($this->path, get_files_in_path($this->path, $excludedFiles), 'project', $name);
        rename_files_with_name($this->path, get_files_in_path($this->path, $excludedFiles), 'Project', ucfirst($name));

        replace_in_file_contents($this->path, get_files_in_path($this->path), 'project', $name);
        replace_in_file_contents($this->path, get_files_in_path($this->path), 'Project', ucfirst($name));
    }
}
