<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackRequest;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbacksController extends Controller
{
    // POST 留言
    public function store(FeedbackRequest $request)
    {
        $data = [];
        $data['email'] = $request->input('email');
        if (Auth::check()) {
            $user = Auth::user();
            $data['user_id'] = $user->id;
        }
        Feedback::create($data);
        return redirect()->back();
    }
}
