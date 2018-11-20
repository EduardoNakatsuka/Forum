<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;


class ParticipateInForumTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unauthenticated_users_may_not_add_replies()
    {
        // $this->withoutExceptionHandling();
        // $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->withExceptionHandling()
            ->post('/threads/some-channel/1/replies', [])
            ->assertRedirect('/login');
    }

    /** @test */
    function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->be($user = create('App\User'));

        $thread = create('App\Thread');
        $reply = make('App\Reply');

        $this->post($thread->path().'/replies', $reply->toArray());

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);
    }

    /** @test */
    function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create('App\Thread');
        $reply = make('App\Reply', ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /** @test */
    function authorized_users_can_delete_replies()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}")->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    /** @test */
    function unauthorized_users_cannot_delete_replies()
    {
        $this->withExceptionHandling(); //show us the exceptions thrown in the test
        
        $reply = create('App\Reply'); //create a thread and reply in it.

        $this->delete("/replies/{$reply->id}") //execute a delete request in the reply's id
            ->assertRedirect('login'); //assert that we are redirected to login page

        $this->signIn() //we will sign in a random
        ->delete("/replies/{$reply->id}") //Make the random user try to delete it
        ->assertStatus(403); //we need to see a forbidden status of course
    }
    
    // /** @test */
    // function authorized_users_can_create_replies()
    // {
    //     $this->signIn(); //given we are signed in
    //     $reply = create('App\Reply', ['user_id' => auth()->id()]); //create a reply in which the user's id is the same of the auth user

    //     $this->delete("/replies/{$reply->id}")->assertStatus(302); //then when we submit the request to delete the reply and assert the original link has moved
        
    //     $this->assertDatabaseMissing('replies', ['id' => $reply->id]); //it should have been deleted and missing from DB
    // }

    /** @test */
    function authorized_users_can_update_replies()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->patch("/replies/{$reply->id}", ['body' => 'You been changed, fool.']);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => 'You been changed, fool.']);
    }

    /** @test */
    function unauthorized_users_cannot_update_replies()
    {
        $this->withExceptionHandling(); //show us the exceptions thrown in the test
        
        $reply = create('App\Reply'); //create a thread and reply in it.

        $this->patch("/replies/{$reply->id}") //execute a patch(edit) request in the reply's id
            ->assertRedirect('login'); //assert that we are redirected to login page

        $this->signIn() //we will sign in a random
        ->patch("/replies/{$reply->id}") //Make the random user try to patch it
        ->assertStatus(403); //we need to see a forbidden status of course
    }

    /** @test */
    function replies_that_cotain_spam_may_not_be_created()
    {
        $this->signIn();

        $thread = create('App\Thread');
        $reply = make('App\Reply', [
            'body' => 'Yahoo Customer Support'
        ]);

        $this->expectException(\Exception::class);
        
        $this->post($thread->path() . '/replies', $reply->toArray());
    }
}
