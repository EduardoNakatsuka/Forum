<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'email', 'password', 'remember_token',

    ];

    protected $casts = [
        'confirmed' => 'boolean' //will always treat confirmed in db as boolean
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function threads()
    {
        return $this->hasMany(Thread::class)->latest();
    }

    public function activity()
    {
        return $this->hasMany(Activity::class); //this way we show the user has many activities lol
    }

    public function confirm()
    {
        $this->confirmed = true;
        $this->confirmation_token = null;
        $this->save();
    }

    public function isAdmin()
    {
        return in_array($this->name, ['JohnDoe', 'JaneDoe']);
    }

    public function visitedThreadCacheKey($thread)
    {
        return sprintf("users.%s.visits.%s", $this->id, $thread->id);
    }

    public function read($thread)
    {
        //simulate that the user visited the thread
        cache()->forever(
            $this->visitedThreadCacheKey($thread), 
            \Carbon\Carbon::now()
        );
    }

    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    public function getAvatarPathAttribute($avatar)
    {
        // return $this->avatar_path ? 'storage/' . $this->avatar_path : 'images/avatars/default.jpg';
        return asset($avatar ? 'storage/' . $avatar : 'images/avatars/default.jpg');
        // return asset($avatar ?: 'images/avatars/default.jpg');
    }
}
