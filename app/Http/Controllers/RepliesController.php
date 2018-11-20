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


    
    public function store($channelId, Thread $thread)
    {
        try {
            $this->validateReply();
    
            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
            ]);
        } catch (\Exception $e) {
            return response(
                'Sorry, your reply could not be saved at this time.', 422
            );
        }
        return $reply->load('owner');
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
        
        try {
            $this->validateReply();
            
            $reply->update(request(['body'])); //update the reply's body for the new requested body
        } catch (\Exception $e) {
            return response(
                'Sorry, your reply could not be saved at this time.', 422
            );
        }
    }


    protected function validateReply()
    {
        $this->validate(request(), ['body' => 'required']);
        resolve(Spam::class)->detect(request('body'));
    }

}
