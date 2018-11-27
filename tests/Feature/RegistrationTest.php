<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Auth\Events\Registered;
use App\User;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake(); //this wont send the confirmation email to the real email

        $this->post(route('register'), [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'foobar',
            'password_confirmation' => 'foobar'
        ]);

        Mail::assertQueued(PleaseConfirmYourEmail::class);
    }

    /** @test */
    function users_can_fully_confirm_their_email_addresses()
    {
        Mail::fake();
        $this->post(route('register'), [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'foobar',
            'password_confirmation' => 'foobar'
        ]);

        $user = User::whereName('John')->first();

        $this->assertFalse($user->confirmed);
        $this->assertNotNull($user->confirmation_token);
        
        $response = $this->get(route(
            'register.confirm', ['token' => $user->confirmation_token]))
            ->assertRedirect(route('threads'));
    
        $this->assertTrue($user->fresh()->confirmed);
        $this->assertNull($user->fresh()->confirmation_token);
    }

    /** @test */
    function confirming_an_invalid_token()
    {
        $this->get(route(
            'register.confirm', ['token' => 'invalid']))
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash', 'Unknown token.');
    }
}