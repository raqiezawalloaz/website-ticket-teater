@extends('layouts.admin')

@section('title', 'Kelola Sertifikat - ' . $event->nama_event)
@section('header_title', 'Kelola Sertifikat')
@section('header_subtitle', $event->nama_event)

@section('styles')
<style>
    .participant-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .participant-table thead {
        background: #f8fafc;
    }
    .participant-table th {
        text-align: left;
        padding: 15px;
        font-weight: 600;
        color: #64748b;
        font-size: 0.85rem;
        text-transform: uppercase;
        border-bottom: 2px solid #e2e8f0;
    }
    .participant-table td {
        padding: 15px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .participant-table tbody tr:hover {
        background: #f8fafc;
    }
    .btn-download {
        background: #10b981;
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-download:hover {
        background: #059669;
    }
    .event-info-box {
        background: linear-gradient(135deg, #0061ff 0%, #6366f1 100%);
        color: white;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 25px;
    }
    .event-info-box h2 {
        margin: 0 0 10px 0;
        font-size: 1.5rem;
    }
    .event-info-box p {
        margin: 5px 0;
        opacity: 0.9;
    }
    .btn-back {
        background: #f1f5f9;
        color: #475569;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
    }
    .btn-back:hover {
        background: #e2e8f0;
    }
</style>
@endsection

@section('content')
<a href="{{ route('admin.certificates.index') }}" class="btn-back">
    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Event
</a>

<div class="event-info-box">
    <h2>{{ $event->nama_event }}</h2>
    <p><i class="far fa-calendar"></i> {{ $event->tanggal_event->format('d F Y, H:i') }} WIB</p>
    <p><i class="fas fa-map-marker-alt"></i> {{ $event->lokasi ?? 'Online' }}</p>
    <p><i class="fas fa-users"></i> Total Peserta Eligible: <strong>{{ $transactions->count() }}</strong></p>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h3 style="margin: 0; color: #1e293b;">Daftar Peserta & Sertifikat</h3>
        
        @if($transactions->count() > 0)
            <div style="color: #64748b; font-size: 0.9rem;">
                Showing {{ $transactions->count() }} participant(s)
            </div>
        @endif
    </div>

    @if($transactions->count() > 0)
        <table class="participant-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Nama Peserta</th>
                    <th style="width: 20%;">Email</th>
                    <th style="width: 15%;">Kode Tiket</th>
                    <th style="width: 15%;">Tanggal Daftar</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $index => $transaction)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $transaction->user->name }}</strong>
                        </td>
                        <td style="color: #64748b; font-size: 0.9rem;">
                            {{ $transaction->user->email }}
                        </td>
                        <td>
                            <code style="background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem;">
                                {{ $transaction->ticket_code }}
                            </code>
                        </td>
                        <td style="color: #64748b; font-size: 0.9rem;">
                            {{ $transaction->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <span style="background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">
                                <i class="fas fa-check-circle"></i> Lunas
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.certificates.download', $transaction->id) }}" class="btn-download" title="Download Sertifikat">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 60px; color: #94a3b8;">
            <i class="fas fa-user-slash" style="font-size: 3rem; margin-bottom: 15px;"></i>
            <h3 style="color: #64748b; margin: 10px 0;">Belum Ada Peserta</h3>
            <p>Event ini belum memiliki peserta yang lunas pembayaran.</p>
        </div>
    @endif
</div>
@endsection
