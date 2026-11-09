<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ApiTokenExpiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_token_is_accepted_before_expiry(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('mobile-kasir', ['products:read'], now()->addDay())->plainTextToken;

        $this->withToken($token)->getJson('/api/v1/products')->assertOk();
    }

    public function test_expired_token_is_rejected(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('mobile-kasir', ['products:read'], now()->addDay())->plainTextToken;

        // majukan jam 1 hari + 1 menit, token sudah lewat masa berlakunya
        Carbon::setTestNow(now()->addDay()->addMinute());

        $this->withToken($token)->getJson('/api/v1/products')->assertUnauthorized(); // 401

        Carbon::setTestNow(); // kembalikan jam normal
    }
}
