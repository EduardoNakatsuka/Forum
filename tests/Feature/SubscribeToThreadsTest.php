<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscribeToThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_subscribe_to_threads()
    {
        $this->signIn();
        
        $thread = create('App\Thread');

        $this->post($thread->path() . '/subscriptions');

        //everytime a reply is left it should notify the subscribed user
        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply here'
        ]);

        $this->assertCount(1, auth()->user()->notifications);
    }

        /** @test */
    public function a_user_can_unsubscribe_from_threads()
    {
        $this->signIn();
        
        $thread = create('App\Thread');
        
        $thread->subscribe();

        $this->delete($thread->path() . '/subscriptions');

        $this->assertCount(0, $thread->subscriptions);
    }

}
