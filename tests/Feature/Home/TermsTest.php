<?php

namespace Tests\Feature\Home;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TermsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_see_terms_page(): void
    {
        $response = $this->get(route('terms'));

        $response->assertStatus(200);
        $response->assertViewIs('terms');
    }
}
