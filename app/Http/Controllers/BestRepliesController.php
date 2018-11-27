<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reply;

class BestRepliesController extends Controller
{
    public function store(Reply $reply)
    {
        $this->authorize('update', $reply->thread);
        // abort_if($reply->thread->user_id !== auth()->id(), 403); we don't need this since we have the authorize for threads policy

        // $reply->thread->update(['best_reply_id' => $reply->id]);
        $reply->thread->MarkBestReply($reply);
    }
}
