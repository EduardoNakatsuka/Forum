<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Thread;
use App\Reply;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store($channelId, Thread $thread)
    {
        $this->validate(request(), ['body' => 'required']);
        
        $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);

        return back()->with('flash', 'Your reply has been left.');
    }

    public function destroy(Reply $reply) //we have to accept the reply and demonstrate the $ of it
    {
        $this->authorize('update', $reply);

        $reply->delete();
        
        return back();
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply); //this will make the ppl who are authorized(authservice) update the reply

        $reply->update(request(['body'])); //update the reply's body for the new requested body
    }
}
