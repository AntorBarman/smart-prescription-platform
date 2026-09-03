<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature', 'Unit');

// Custom helpers
function createUser($role = 'admin')
{
    $user = \App\Models\User::factory()->create();
    $user->assignRole($role);
    return $user;
}