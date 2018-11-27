<?php

namespace Tests\Unit;

use App\Notifications\ThreadWasUpdated;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redis;


class ThreadTest extends TestCase
{
    use RefreshDatabase;

    protected $thread;

    public function setUp()
    {
        parent::setUp();
        $this->thread = factory('App\Thread')->create();
    }
    
    /** @test */
    function a_thread_has_a_path()
    {
        $thread = create('App\Thread');

        $this->assertEquals(
            "/threads/{$thread->channel->slug}/{$thread->slug}", $thread->path()
        );
    }

    /** @test */
    function a_thread_has_a_creator()
    {
        $this->assertInstanceOf(User::class, $this->thread->creator);
    }

    /** @test */
    function a_thread_has_replies()
    {
        $this->assertInstanceOf
        ('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    /** @test */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);
        
        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
    {
        Notification::fake(); //replace the instance with a fake version ((localy, no email, no nothing))
        
        $this->signIn()
            ->thread
            ->subscribe()
            ->addReply([
                'body' => 'Foobar',
                'user_id' => 999
        ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class); //make sure a ThreadWasUpdated notification was sent to the user
    }

    /** @test */
    public function a_thread_belongs_to_a_channel()
    {
        $thread = create('App\Thread');

        $this->assertInstanceOf('App\Channel', $thread->channel);
    }

    /** @test */
    function a_thread_can_be_subscribed_to()
    {
        //given we have a thread
        $thread = create('App\Thread');

        //when the user subscribes to the thread
          $thread->subscribe($userId = 1);
        
        //then we should be able to fetch all threads that the user has subscribed to
        $this->assertEquals(
            1,
            $thread->subscriptions()->where('user_id', $userId)->count()
        );
    }
    
    /** @test */
    function a_thread_can_be_unsubscribed_from()
    {
        //given we have a thread
        $thread = create('App\Thread');

        //and a user who is subscribed to the thread
        $thread->subscribe($userId = 1);

        $thread->unsubscribe($userId); //unsubscribe the user

        $this->assertCount(0, $thread->subscriptions); //and let's make sure the thread has no subscriptions
    }

    /** @test */
    function it_knows_if_the_auth_user_is_subscribed_to_it()
    {
        $thread = create('App\Thread');

        $this->signIn();
        
        $this->assertFalse($thread->isSubscribedTo);
        
        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }

    /** @test */
    function a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        $this->signIn();

        $thread = create('App\Thread');

        tap(auth()->user(), function ($user) use ($thread) {
            $this->assertTrue($thread->hasUpdatesFor($user));
    
            $user->read($thread);
    
            $this->assertFalse($thread->hasUpdatesFor($user));
        });
    }

    /** @test */
    function a_thread_may_be_locked()
    {
        $this->assertFalse($this->thread->locked);

        $this->thread->lock();

        $this->assertTrue($this->thread->locked);
    }
}

