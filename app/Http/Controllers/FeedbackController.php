<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            $feedbacks = Feedback::with('user')->latest()->paginate(10);
            return view('admin.feedback.index', compact('feedbacks'));
        }
        return view('user.feedback.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:Event,Tenant,Sponsor,Sistem',
            'message' => 'required|string|min:10',
        ]);

        Feedback::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'message' => $request->message,
        ]);

        return redirect()->route('feedback.index')->with('success', 'Terima kasih atas feedback Anda!');
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $feedback = Feedback::findOrFail($id);
        $feedback->delete();

        return back()->with('success', 'Feedback berhasil dihapus.');
    }
}
