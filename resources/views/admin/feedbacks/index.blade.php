@extends('layouts.admin')

@section('title', 'Manajemen Feedback')
@section('header_title', 'Manajemen Feedback')
@section('header_subtitle', 'Lihat semua ulasan dan feedback dari pengguna.')

@section('styles')
<style>
    .feedback-card {
        background: white; border: 1px solid #f1f5f9; border-radius: 12px; padding: 20px;
        margin-bottom: 15px; transition: all 0.2s;
    }
    .feedback-card:hover { transform: translateY(-2px); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .star-rating { color: #fbbf24; margin-right: 10px; }
    
    .btn-export {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white; padding: 12px 25px; border-radius: 12px;
        text-decoration: none; font-weight: bold; display: inline-flex;
        align-items: center; gap: 8px; box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.2);
        transition: 0.3s;
    }
    .btn-export:hover {
        transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.3);
    }
</style>
@endsection

@section('content')

<!-- Navigation Tabs -->
<div class="nav-tabs">
    <a href="{{ route('admin.certificates.index') }}" class="nav-tab">Kelola Sertifikat</a>
    <a href="{{ route('admin.feedbacks.index') }}" class="nav-tab active">Data Feedback</a>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div style="color: #64748b;">
            Total Ulasan Masuk: <strong>{{ $feedbacks->count() }}</strong>
        </div>
        <a href="{{ route('admin.feedbacks.exportPdf') }}" class="btn-export">
            <i class="fas fa-file-pdf"></i> Export ke PDF
        </a>
    </div>

    @forelse($feedbacks as $feedback)
        <div class="feedback-card">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                <div>
                    <div style="font-weight: bold; font-size: 1.1rem; color: #1e293b; margin-bottom: 4px;">
                        Event: {{ $feedback->event->nama_event }}
                    </div>
                    <div style="font-size: 0.9rem; color: #64748b;">
                        oleh <span style="color: #3b82f6;">{{ $feedback->user->name }}</span>
                        &bull; {{ $feedback->created_at->format('d M Y H:i') }}
                    </div>
                </div>
                <div style="text-align: right;">
                    <div class="star-rating">
                        @for($i=1; $i<=5; $i++)
                            <i class="fas fa-star" style="{{ $i <= $feedback->rating ? '' : 'color: #e2e8f0;' }}"></i>
                        @endfor
                        <span style="font-weight: bold; color: #333; margin-left: 5px;">{{ $feedback->rating }}.0</span>
                    </div>
                </div>
            </div>
            
            <div style="background: #f8fafc; padding: 15px; border-radius: 8px; font-style: italic; color: #475569;">
                "{{ $feedback->comment }}"
            </div>
            
            <div style="margin-top: 15px; display: flex; justify-content: flex-end;">
                 <a href="{{ route('admin.events.show', $feedback->event->id) }}" style="font-size: 0.85rem; color: #6366f1; text-decoration: none;">
                    Lihat Detail Event &rarr;
                 </a>
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 40px; color: #94a3b8;">
            <i class="far fa-comment-dots" style="font-size: 3rem; margin-bottom: 15px;"></i>
            <p>Belum ada ulasan yang masuk.</p>
        </div>
    @endforelse
</div>

@endsection
