<?php

namespace Tests\Feature\Mail;

use App\Mail\ProjectInvitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use ReflectionException;
use Tests\TestCase;

class ProjectInvitationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @throws ReflectionException
     */
    public function test_project_invitation_mailable_builds_correctly()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $project = $user->currentProject();

        $mailable = new ProjectInvitation($project);
        $built = $mailable->build();

        // Assert
        $this->assertEquals(__('Project Invitation'), $built->subject);

        $viewData = $built->buildViewData();

        $this->assertEquals(
            route('projects.invitations.accept', ['project' => $project]),
            $viewData['acceptUrl']
        );

        $this->assertEquals('emails.project-invitation', $built->markdown);
    }

    public function test_project_invitation_can_be_sent()
    {
        // Arrange
        Mail::fake();

        /** @var User $user */
        $user = User::factory()->create();
        $project = $user->currentProject();

        // Act
        Mail::to('test@example.com')->send(new ProjectInvitation($project));

        // Assert
        Mail::assertSent(ProjectInvitation::class, function (ProjectInvitation $mail) use ($project) {
            return $mail->project->is($project);
        });
    }
}
