<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
        //given I have a signed in user (john)
        $john = create('App\User', ['name' => 'JohnDoe']);
        $this->signIn($john);
        //and another user (mary)
        $mary = create('App\User', ['name' => 'MaryJane']);
        //if we have a thread
        $thread = create('App\Thread');
        //and john replies and mentions mary
        $reply = make('App\Reply', [
            'body' => '@MaryJane look at that'
        ]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray());

        //then mary should be notified

        $this->assertCount(1, $mary->notifications);
    }
}
