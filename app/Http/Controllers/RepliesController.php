<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Thread;
use App\Reply;
use App\Http\Forms\CreatePostForm;

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



    public function store($channelId, Thread $thread, CreatePostForm $form)
    {
        if ($thread->locked) {
            return response('Thread is locked.', 422);
        }

        return $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ])->load('owner');
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

        $this->validate(request(), ['body' => 'required|spamFree']);

        $reply->update(request(['body'])); //update the reply's body for the new requested body
    }


    // protected function validateReply()
    // {
    //     $this->validate(request(), ['body' => 'required|spamFree']);
    // }

}
