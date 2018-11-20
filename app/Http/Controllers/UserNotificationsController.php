<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserNotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return auth()->user()->unreadNotifications;
    }

    public function destroy(User $user, $notificationId)
    {
        // $user->notifications()->find($notificationId)->markAsRead(); //with this we have a problem that we can delete other ppl''s answer

        auth()->user()->notifications()->findOrFail($notificationId)->markAsRead();
    }
}
