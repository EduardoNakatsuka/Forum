<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favorable, RecordsActivity;

    protected $guarded = [];

    protected $with = ['owner', 'favorites'];
    
    protected $appends = ['favoritesCount', 'isFavorited', 'isBest']; //is there anything we want to append the array representation of that? it passes to json!
    // protected $fillable = ['body', 'user_id'];

    protected static function boot()
    {
        parent::boot(); //override a method called boot

        static::created(function ($reply) { //when we have created a new reply as part of that i need to go to its original thread and incrememt a replies count
            $reply->thread->increment('replies_count');
        });

        static::deleted(function ($reply) { //when we have deleted a  as part of that i need to go to its original thread and decrement a replies count
            $reply->thread->decrement('replies_count');
        });
    }
    
    
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    public function mentionedUsers()
    {
        preg_match_all('/@([\w\-]+)/', $this->body, $matches);

        return $matches[1];
    }

    public function path()
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }

    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace('/@([\w\-]+)/', '<a href="/profiles/$1">$0</a>', $body); // @([^\s]+) = starts with @ untill there is a space and ^NOTT^ space and exclude the @ (simbol) \. removes periods @[\w\-]+ gets every words and every dashes too
    }    

    public function isBest()
    {
        return $this->thread->best_reply_id == $this->id; //get the best reply's id associated wwith the thread and make sure is is the same of this replyid
    }

    public function getIsBestAttribute()
    {
        return $this->isBest();
    }
}
