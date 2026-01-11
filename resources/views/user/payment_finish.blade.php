@extends('layouts.user')

@section('title', 'Pembayaran Selesai')

@section('content')
<div style="max-width: 600px; margin: 40px auto; text-align: center; background: white; padding: 50px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">
    <div style="width: 80px; height: 80px; background: #dcfce7; color: #22c55e; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; font-size: 2.5rem;">
        <i class="fas fa-check"></i>
    </div>
    
    <h1 style="font-size: 1.8rem; font-weight: 800; color: #1e293b; margin-bottom: 10px;">Terima Kasih!</h1>
    <p style="color: #64748b; font-size: 1.05rem; line-height: 1.6; margin-bottom: 35px;">
        Pesanan Anda telah kami terima dan sedang dalam proses verifikasi otomatis oleh sistem. 
        E-tiket Anda akan muncul di halaman "Tiket Saya" dalam beberapa saat.
    </p>

    <div style="display: flex; gap: 15px; justify-content: center;">
        <a href="{{ route('user.tickets.index') }}" style="background: #6366f1; color: white; padding: 12px 28px; border-radius: 10px; text-decoration: none; font-weight: 600; transition: 0.3s;" onmouseover="this.style.background='#4f46e5'" onmouseout="this.style.background='#6366f1'">
            <i class="fas fa-ticket-alt"></i> Lihat Tiket Saya
        </a>
        <a href="{{ route('dashboard') }}" style="background: #f1f5f9; color: #475569; padding: 12px 28px; border-radius: 10px; text-decoration: none; font-weight: 600; transition: 0.3s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
            Dashboard
        </a>
    </div>
    
    <div style="margin-top: 50px; padding-top: 30px; border-top: 1px solid #f1f5f9; font-size: 0.85rem; color: #94a3b8;">
        Butuh bantuan? Hubungi kami melalui WhatsApp di <a href="#" style="color: #6366f1; text-decoration: none;">+62 812 3456 7890</a>
    </div>
</div>
@endsection
