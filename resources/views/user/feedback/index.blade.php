@extends('layouts.user')

@section('title', 'Feedback - Campus Event')
@section('header_title', 'Feedback')
@section('header_subtitle', 'Berikan masukan atau laporkan masalah Anda')

@section('content')
<div class="row" style="max-width: 800px; margin: 0 auto;">
    <div class="card" style="background: white; padding: 30px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
        @if(session('success'))
            <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 12px; margin-bottom: 25px; border-left: 5px solid #22c55e; display: flex; align-items: center; gap: 12px;">
                <i class="fas fa-check-circle" style="font-size: 1.2rem;"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('feedback.store') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Tipe Feedback</label>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 12px;">
                    <label style="cursor: pointer;">
                        <input type="radio" name="type" value="Event" style="display: none;" checked>
                        <div class="type-card">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Event</span>
                        </div>
                    </label>
                    <label style="cursor: pointer;">
                        <input type="radio" name="type" value="Tenant" style="display: none;">
                        <div class="type-card">
                            <i class="fas fa-store"></i>
                            <span>Tenant</span>
                        </div>
                    </label>
                    <label style="cursor: pointer;">
                        <input type="radio" name="type" value="Sponsor" style="display: none;">
                        <div class="type-card">
                            <i class="fas fa-handshake"></i>
                            <span>Sponsor</span>
                        </div>
                    </label>
                    <label style="cursor: pointer;">
                        <input type="radio" name="type" value="Sistem" style="display: none;">
                        <div class="type-card">
                            <i class="fas fa-laptop-code"></i>
                            <span>Sistem</span>
                        </div>
                    </label>
                </div>
            </div>

            <div style="margin-bottom: 25px;">
                <label for="message" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Pesan / Detail Feedback</label>
                <textarea name="message" id="message" rows="5" 
                    placeholder="Ceritakan detail feedback atau masalah yang Anda temukan..."
                    style="width: 100%; border: 1px solid #e2e8f0; border-radius: 12px; padding: 15px; font-size: 0.95rem; transition: 0.3s; resize: vertical;"
                    onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 3px rgba(99, 102, 241, 0.1)';"
                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';"
                >{{ old('message') }}</textarea>
                @error('message')
                    <span style="color: #ef4444; font-size: 0.8rem; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" style="width: 100%; background: var(--primary); color: white; border: none; padding: 16px; border-radius: 12px; font-weight: 700; font-size: 1rem; cursor: pointer; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 10px;">
                <i class="fas fa-paper-plane"></i> Kirim Feedback
            </button>
        </form>
    </div>
</div>
@endsection

@section('styles')
<style>
    .type-card {
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 15px;
        text-align: center;
        transition: 0.3s;
        display: flex;
        flex-direction: column;
        gap: 8px;
        color: #64748b;
    }
    .type-card i { font-size: 1.5rem; }
    .type-card span { font-size: 0.85rem; font-weight: 600; }
    
    input[type="radio"]:checked + .type-card {
        background: #f0f4ff;
        border-color: var(--primary);
        color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1);
    }

    .rating-stars {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 5px;
    }
    .rating-stars input { display: none; }
    .rating-stars label {
        color: #cbd5e1;
        font-size: 1.4rem;
        cursor: pointer;
        transition: 0.2s;
    }
    .rating-stars label:hover,
    .rating-stars label:hover ~ label {
        color: #fbbf24;
    }
    .rating-stars input:checked ~ label {
        color: #fbbf24;
    }
</style>
@endsection
