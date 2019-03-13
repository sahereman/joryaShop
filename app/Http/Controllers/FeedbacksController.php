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
        $data['type'] = $request->input('type');
        if (Auth::check()) {
            $user = Auth::user();
            $data['user_id'] = $user->id;
        }
        $feedback = Feedback::create($data);
        if ($request->ajax()) {
            if ($feedback) {
                return response()->json([
                    'code' => 200,
                    'message' => 'success',
                ], 200);
            } else {
                return response()->json([
                    'code' => 422,
                    'message' => 'Unprocessable Entity',
                ], 422);
            }
        } else {
            return redirect()->back(302);
        }
    }
}
