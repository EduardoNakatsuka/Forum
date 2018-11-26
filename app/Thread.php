<?php

namespace App;

use App\Events\ThreadHasNewReply;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Support\Facades\Redis;
use App\Events\ThreadReceivedNewReply;

class Thread extends Model
{
    use Favorable, RecordsActivity, RecordsVisits;
    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];
    
    protected static function boot()
    {
        parent::boot();

        // static::addGlobalScope('replyCount', function ($builder) {
        //     $builder->withCount('replies');
        // });

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });

    }
    
    
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }
    
    public function replies()
    {
        return $this->hasMany(Reply::class);
            // ->withCount('favorites')
            // ->with('owner');
    }

    public function creator()
    {
        return $this->belongsTo(User::Class, 'user_id');
    }
    
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
    
    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply); //it creates and saves the new reply

        event(new ThreadReceivedNewReply($reply));
        
        return $reply;
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions() //find all subscriptions
        ->where('user_id', $userId ?: auth()->id()) //filtered for the ones that have YOUR user id
        ->delete(); //delete them
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }
    
    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
        ->where('user_id', auth()->id())
        ->exists();
    }

    public function hasUpdatesFor($user)
    {
        $key = auth()->user()->visitedThreadCacheKey($this);
        
        return $this->updated_at > cache($key);
    }

}
