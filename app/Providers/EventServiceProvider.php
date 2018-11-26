<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    // protected $listen = [
    //     Registered::class => [
    //         SendEmailVerificationNotification::class,
    //     ],
    // ];    
    protected $listen = [
        'App\Events\ThreadReceivedNewReply' => [ //when thread receives a new reply
            'App\Listeners\NotifyMentionedUsers', //then fire the notify mentionedusers 
            'App\Listeners\NotifySubscribers' //notifies the subscribers lol
        ]
    ];    


    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
