@extends('layouts.admin')

@section('title', 'Feedback Moderation Desk – Yatri Sahayata')
@section('header_title', 'Pilgrim Feedback & Reviews Moderation')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
    <div>
        <h3 style="color:#0a192f; font-size:1.2rem; font-weight:800; margin:0;">Pilgrim Community Reviews</h3>
        <span style="font-size:0.8rem; color:#64748b;">Review and moderate ratings submitted by registered and guest yatris</span>
    </div>
    <a href="{{ route('admin.export', 'feedback') }}" class="btn btn-sm btn-navy">📥 Export Feedback CSV</a>
</div>

<div class="table-responsive">
    <table class="yatri-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pilgrim Name</th>
                <th>Circuit</th>
                <th>Rating</th>
                <th>Aspect</th>
                <th>Review Comment</th>
                <th>Submitted</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($feedbacks as $fb)
            <tr>
                <td>{{ $fb->id }}</td>
                <td>
                    <strong>{{ $fb->pilgrim_name }}</strong><br>
                    <span style="font-size:0.75rem; color:#64748b;">{{ $fb->email ?? 'Guest' }}</span>
                </td>
                <td><strong>{{ $fb->circuit }}</strong></td>
                <td>
                    <span style="color:#f59e0b; font-weight:700;">{{ str_repeat('⭐', $fb->rating) }}</span>
                </td>
                <td>{{ $fb->service_aspect }}</td>
                <td style="font-size:0.85rem; color:#334155;">{{ $fb->comment }}</td>
                <td style="font-size:0.75rem; color:#64748b;">
                    {{ \Carbon\Carbon::parse($fb->created_at)->format('d M Y') }}
                </td>
                <td>
                    <span class="card-badge {{ $fb->is_moderated ? 'badge-verified' : 'badge-gold' }}" style="margin:0; font-size:0.7rem;">
                        {{ $fb->is_moderated ? 'APPROVED' : 'PENDING' }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center; padding:3rem; color:#64748b;">No feedback submissions recorded yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:1.5rem;">
    {{ $feedbacks->links() }}
</div>
@endsection
