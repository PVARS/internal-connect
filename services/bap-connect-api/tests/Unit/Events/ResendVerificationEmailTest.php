<?php

namespace Events;

use App\Events\ResendVerificationEmail;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ResendVerificationEmailTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        User::truncate();
    }

    protected function tearDown(): void
    {
        User::truncate();
        parent::tearDown();
    }

    public function test_resend_verification_email_success()
    {
        Event::fake();

        $user = User::factory()->create();
        \event(new ResendVerificationEmail($user));

        Event::assertDispatched(ResendVerificationEmail::class, fn ($event) => $event->user === $user);
        Event::assertDispatchedTimes(ResendVerificationEmail::class);
    }

    public function test_resend_verification_email_fail()
    {
        Event::fake();

        $user = User::factory()->create();

        Event::assertNotDispatched(ResendVerificationEmail::class, fn ($event) => $event->user === $user);
        Event::assertDispatchedTimes(ResendVerificationEmail::class, 0);
    }
}
