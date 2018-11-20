<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Thread;
use App\Reply;
use App\Inspections\Spam;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(10);
    }

    public function store($channelId, Thread $thread, Spam $spam)
    {
        $this->validate(request(), ['body' => 'required']);
        $spam->detect(request('body'));
        
        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);

        if (request()->expectsJson()) {
            return $reply->load('owner');
        }

        return back()->with('flash', 'Your reply has been left.');
    }

    public function destroy(Reply $reply) //we have to accept the reply and demonstrate the $ of it
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }
        
        return back();
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply); //this will make the ppl who are authorized(authservice) update the reply
        
        $this->validate(request(), ['body' => 'required']);
        
        $reply->update(request(['body'])); //update the reply's body for the new requested body
    }
}
