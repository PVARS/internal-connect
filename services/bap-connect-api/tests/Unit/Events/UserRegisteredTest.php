<?php

namespace Events;

use App\Events\UserRegistered;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UserRegisteredTest extends TestCase
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

    public function test_user_registered_success()
    {
        Event::fake();

        $user = User::factory()->create();
        \event(new UserRegistered($user));

        Event::assertDispatched(UserRegistered::class, fn ($event) => $event->user === $user);
        Event::assertDispatchedTimes(UserRegistered::class);
    }

    public function test_user_registered_fail()
    {
        Event::fake();

        $user = User::factory()->create();

        Event::assertNotDispatched(UserRegistered::class, fn ($event) => $event->user === $user);
        Event::assertDispatchedTimes(UserRegistered::class, 0);
    }
}
