<?php

use App\Mail\ProjectInvitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

test('project invitation mailable builds correctly', function () {
    /** @var User $user */
    $user = User::factory()->create();
    $project = $user->currentProject();

    $mailable = new ProjectInvitation($project);
    $built = $mailable->build();

    // Assert
    expect($built->subject)->toEqual(__('Project Invitation'));

    $viewData = $built->buildViewData();

    expect($viewData['acceptUrl'])->toEqual(route('projects.invitations.accept', ['project' => $project]));

    expect($built->markdown)->toEqual('emails.project-invitation');
});

test('project invitation can be sent', function () {
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
});
