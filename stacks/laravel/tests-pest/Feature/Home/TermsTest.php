<?php

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can see terms page', function () {
    $response = $this->get(route('terms'));

    $response->assertStatus(200);
    $response->assertViewIs('terms');
});
