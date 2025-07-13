<?php

namespace Tests\Feature\Home;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrivacyTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_see_privacy_page(): void
    {
        $response = $this->get(route('privacy'));

        $response->assertStatus(200);
        $response->assertViewIs('privacy');
    }
}
