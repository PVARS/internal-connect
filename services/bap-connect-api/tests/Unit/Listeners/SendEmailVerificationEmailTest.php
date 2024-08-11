<?php

namespace Listeners;

use App\Enums\Gender;
use App\Enums\UserStatus;
use App\Events\UserRegistered;
use App\Listeners\SendEmailVerificationEmail;
use App\Mail\VerificationEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendEmailVerificationEmailTest extends TestCase
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

    public function test_send_email_verification_email_with_user_verified(): void
    {
        Mail::fake();

        $user = User::factory([
            'id' => fake()->uuid(),
            'username' => fake()->userName(),
            'email' => fake()->email(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'gender' => Gender::MALE->value,
            'email_verified_at' => Carbon::now(),
            'status' => UserStatus::ACTIVE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
        ])->create();

        $event = new UserRegistered($user);
        $listener = new SendEmailVerificationEmail();
        $listener->handle($event);

        Mail::assertNotSent(VerificationEmail::class, fn ($mail) => $mail->hasTo($user->email));
        Mail::assertSentCount(0);
    }

    public function test_send_email_verification_email_success(): void
    {
        Mail::fake();

        $user = User::factory()->create();

        $event = new UserRegistered($user);
        $listener = new SendEmailVerificationEmail();
        $listener->handle($event);

        Mail::assertSent(VerificationEmail::class, fn ($mail) => $mail->hasTo($user->email));
        Mail::assertSentCount(1);
    }
}
