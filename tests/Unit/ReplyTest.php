<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class ReplyTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function it_has_an_owner()
    {
        $reply = factory('App\Reply')->create();

        $this->assertInstanceOf(User::class, $reply->owner);
    }
}
