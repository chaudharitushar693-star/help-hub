@extends('layouts.admin')

@section('title', 'Accommodation Directory – Yatri Sahayata')
@section('header_title', 'Verified Pilgrim Accommodations Directory')

@section('content')
<div class="table-responsive">
    <table class="yatri-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Circuit</th>
                <th>Address</th>
                <th>Tariff</th>
                <th>Rating</th>
                <th>Phone</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($accommodations as $a)
            <tr>
                <td>{{ $a->id }}</td>
                <td><strong>{{ $a->name }}</strong></td>
                <td><span class="card-badge badge-info" style="margin:0;">{{ $a->type }}</span></td>
                <td><strong>{{ $a->circuit }}</strong></td>
                <td>{{ Str::limit($a->address, 30) }}</td>
                <td>{{ $a->price_range }}</td>
                <td>⭐ {{ $a->rating }}</td>
                <td><a href="tel:{{ $a->contact_phone }}" style="color:#0284c7;">{{ $a->contact_phone }}</a></td>
                <td>
                    <span class="card-badge {{ $a->is_verified ? 'badge-verified' : 'badge-gold' }}" style="margin:0; font-size:0.7rem;">
                        {{ $a->is_verified ? 'VERIFIED' : 'PENDING' }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div style="margin-top:1.5rem;">
    {{ $accommodations->links() }}
</div>
@endsection
