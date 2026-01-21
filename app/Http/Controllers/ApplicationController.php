<?php

namespace App\Http\Controllers;

use App\Models\CourseApplication;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $applications = CourseApplication::where('user_id', $user->id)
            ->with('reviews')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('applications.index', compact('applications', 'user'));
    }

    public function create()
    {
        return view('applications.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        CourseApplication::create([
            'user_id' => $user->id,
            'course_name' => $request->input('course_name'),
            'start_date' => $request->input('start_date'),
            'payment_method' => $request->input('payment_method'),
            'status' => 'Новая',
        ]);

        return redirect()->route('applications.index');
    }

    public function storeReview(Request $request, CourseApplication $application)
    {
        $user = Auth::user();

        Review::create([
            'user_id' => $user->id,
            'course_application_id' => $application->id,
            'content' => $request->input('content'),
        ]);

        return redirect()->route('applications.index');
    }
}


