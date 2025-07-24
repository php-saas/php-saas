<?php

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can see home page', function () {
    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertViewIs('index');
});
