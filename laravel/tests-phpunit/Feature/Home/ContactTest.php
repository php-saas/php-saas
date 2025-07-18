<?php

namespace Tests\Feature\Home;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_see_contact_page(): void
    {
        $response = $this->get(route('contact'));

        $response->assertStatus(200);
        $response->assertViewIs('contact');
    }
}
