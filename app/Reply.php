<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favorable;

    protected $guarded = [];

    protected $with = ['owner', 'favorites'];
    
    // protected $fillable = ['body', 'user_id'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
