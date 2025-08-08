<?php

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can see privacy page', function () {
    $response = $this->get(route('privacy'));

    $response->assertStatus(200);
    $response->assertViewIs('privacy');
});
