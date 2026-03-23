<?php

namespace App\Http\Controllers;

use App\Models\CourseApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user || !$user->is_admin) {
            abort(403);
        }

        $applications = CourseApplication::orderBy('created_at', 'desc')->get();

        return view('admin.index', compact('applications', 'user'));
    }

    public function updateStatus(Request $request, CourseApplication $application)
    {
        $user = Auth::user();

        if (!$user || !$user->is_admin) {
            abort(403);
        }

        $application->status = $request->input('status');
        $application->save();

        return redirect()->route('admin.panel');
    }
}


