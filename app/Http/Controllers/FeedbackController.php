<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Event; // Import Event untuk validasi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * FITUR HEAD: Admin Dashboard & List Feedback
     */
    public function index()
    {
        // Jika Admin, tampilkan semua feedback (digabung dengan fitur HEAD)
        if (Auth::user()->role === 'admin' || (method_exists(Auth::user(), 'isAdmin') && Auth::user()->isAdmin())) {
            $feedbacks = Feedback::with(['user', 'event'])->latest()->paginate(10);
            return view('admin.feedback.index', compact('feedbacks'));
        }
        
        // Jika User biasa, tampilkan form feedback umum
        return view('user.feedback.index');
    }

    /**
     * HYBRID STORE: Menangani Review Event (Main) DAN Feedback Umum (HEAD)
     */
    public function store(Request $request)
    {
        // SKENARIO 1: User memberikan Rating/Review untuk Event (Logika dari Branch MAIN)
        if ($request->has('event_id')) {
            $request->validate([
                'event_id' => 'required|exists:events,id',
                'rating'   => 'required|integer|min:1|max:5',
                'comment'  => 'nullable|string'
            ]);

            // Cek duplikasi review (Fitur Main)
            $exists = Feedback::where('user_id', Auth::id())
                ->where('event_id', $request->event_id)
                ->exists();

            if ($exists) {
                return back()->with('error', 'Anda sudah memberikan ulasan untuk event ini.');
            }

            Feedback::create([
                'user_id'  => Auth::id(),
                'event_id' => $request->event_id,
                'type'     => 'Event Review', // Kita set otomatis
                'rating'   => $request->rating,
                'message'  => $request->comment, // Mapping 'comment' ke 'message' di DB
            ]);

            return back()->with('success', 'Terima kasih atas ulasan event Anda!');
        }

        // SKENARIO 2: User memberikan Feedback Umum/Sistem (Logika dari Branch HEAD)
        else {
            $request->validate([
                'type'    => 'required|string|in:Event,Tenant,Sponsor,Sistem,Lainnya',
                'message' => 'required|string|min:10',
            ]);

            Feedback::create([
                'user_id' => Auth::id(),
                'type'    => $request->type,
                'message' => $request->message,
                // event_id dan rating biarkan null
            ]);

            return redirect()->route('feedback.index')->with('success', 'Terima kasih atas feedback Anda!');
        }
    }

    /**
     * FITUR HEAD: Hapus Feedback (Admin Only)
     */
    public function destroy($id)
    {
        // Pengecekan role admin yang aman
        if (Auth::user()->role !== 'admin' && !(method_exists(Auth::user(), 'isAdmin') && Auth::user()->isAdmin())) {
            abort(403, 'Unauthorized action.');
        }

        $feedback = Feedback::findOrFail($id);
        $feedback->delete();

        return back()->with('success', 'Feedback berhasil dihapus.');
    }
}