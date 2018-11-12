<?php

namespace Tests\Unit;

use App\Activity;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    /** @test */
    public function it_records_activity_when_a_thread_is_created()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => auth()->id(),
            'subject_id' => $thread->id,
            'subject_type' => 'App\Thread'
        ]);

        $activity = Activity::first(); //this will create the activity and get the first one

        $this->assertEquals($activity->subject->id, $thread->id); //this will assert that the first activity is equal to the thread

    }

    /** @test */
    function it_records_activity_when_a_reply_is_created()
    {
        $this->signIn();

        $reply = create('App\Reply'); //reply also creates a post
        
        $this->assertEquals(2, Activity::count()); //we want to see 2 activities (create and reply) but we need to use RecordsActivity; in reply.php
    }
}
