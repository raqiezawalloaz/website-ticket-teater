@extends('layouts.app')

@section('title', 'Pembayaran Selesai')

@section('content')
<div style="max-width: 600px; margin: 40px auto; text-align: center; background: white; padding: 50px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;">
    
    <!-- Ikon Sukses -->
    <div style="width: 80px; height: 80px; background: #dcfce7; color: #22c55e; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; font-size: 2.5rem;">
        <i class="fas fa-check"></i>
    </div>
    
    <h1 style="font-size: 1.8rem; font-weight: 800; color: #1e293b; margin-bottom: 10px;">Terima Kasih!</h1>
    
    <p style="color: #64748b; font-size: 1.05rem; line-height: 1.6; margin-bottom: 35px;">
        Pesanan Anda telah kami terima. Sistem kami sedang memverifikasi pembayaran Anda secara otomatis. 
        Silakan cek status tiket Anda secara berkala.
    </p>

    <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
        <!-- Button ke Tiket Saya (Sesuai route yang kita fix sebelumnya) -->
        <a href="{{ route('user.tickets.index') }}" style="background: #6366f1; color: white; padding: 12px 28px; border-radius: 10px; text-decoration: none; font-weight: 600; transition: 0.3s; box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.3);">
            <i class="fas fa-ticket-alt"></i> Lihat Tiket Saya
        </a>
        
        <a href="{{ route('dashboard') }}" style="background: #f1f5f9; color: #475569; padding: 12px 28px; border-radius: 10px; text-decoration: none; font-weight: 600; transition: 0.3s; border: 1px solid #e2e8f0;">
            Ke Dashboard
        </a>
    </div>
    
    <div style="margin-top: 50px; padding-top: 30px; border-top: 1px solid #f1f5f9; font-size: 0.85rem; color: #94a3b8;">
        Butuh bantuan? Hubungi panitia atau admin jika tiket belum muncul dalam 1x24 jam.
    </div>
</div>
@endsection