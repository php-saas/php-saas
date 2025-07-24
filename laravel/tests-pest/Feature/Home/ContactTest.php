<?php

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can see contact page', function () {
    $response = $this->get(route('contact'));

    $response->assertStatus(200);
    $response->assertViewIs('contact');
});
