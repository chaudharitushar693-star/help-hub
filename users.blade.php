@extends('layouts.admin')

@section('title', 'User & Role Access Management – Yatri Sahayata')
@section('header_title', 'User & Role Access Control')

@section('content')
<div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; padding:1.5rem; box-shadow:var(--shadow-sm);">
    <!-- Search Bar -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
        <form action="{{ route('admin.users') }}" method="GET" style="display:flex; gap:0.5rem; flex:1; max-width:400px;">
            <input type="text" name="search" class="form-control" placeholder="Search by name, email, or phone..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-navy btn-sm">Filter</button>
        </form>
        <span style="font-size:0.85rem; color:#64748b;">Showing {{ $users->total() }} registered members</span>
    </div>

    <div class="table-responsive">
        <table class="yatri-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User / Name</th>
                    <th>Email Address</th>
                    <th>Mobile</th>
                    <th>Current Role</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr>
                    <td>{{ $u->id }}</td>
                    <td>
                        <strong>{{ $u->name }}</strong>
                        @if($u->id === Auth::id())
                            <span style="font-size:0.7rem; background:#fef3c7; color:#b45309; padding:2px 6px; border-radius:4px; font-weight:700; margin-left:4px;">YOU</span>
                        @endif
                    </td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->phone ?? '—' }}</td>
                    <td>
                        <form action="{{ route('admin.users.role', $u->id) }}" method="POST" style="margin:0; display:flex; gap:4px;">
                            @csrf
                            <select name="role_id" onchange="this.form.submit()" style="font-size:0.75rem; padding:2px 6px; border-radius:4px; border:1px solid #cbd5e1; {{ $u->isAdmin() ? 'font-weight:700; color:#b45309;' : '' }}">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ $u->role_id === $role->id ? 'selected' : '' }}>
                                        {{ $role->display_name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td>
                        <span class="card-badge {{ $u->is_active ? 'badge-verified' : 'badge-emergency' }}" style="margin:0; font-size:0.7rem;">
                            {{ $u->is_active ? 'ACTIVE' : 'DEACTIVATED' }}
                        </span>
                    </td>
                    <td style="font-size:0.75rem; color:#64748b;">
                        {{ \Carbon\Carbon::parse($u->created_at)->format('d M Y') }}
                    </td>
                    <td>
                        @if($u->id !== Auth::id())
                        <form action="{{ route('admin.users.toggle', $u->id) }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $u->is_active ? 'btn-secondary' : 'btn-primary' }}" style="padding:2px 8px; font-size:0.72rem;">
                                {{ $u->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        @else
                        <span style="font-size:0.75rem; color:#94a3b8;">Protected</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top:1.5rem;">
        {{ $users->links() }}
    </div>
</div>
@endsection
