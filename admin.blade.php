<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Control Center – Yatri Sahayata')</title>
    <link rel="stylesheet" href="{{ asset('css/yatri-main.css') }}">
    <style>
        .admin-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }
        .admin-sidebar {
            background: #0a192f;
            color: #ffffff;
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #1e293b;
        }
        .admin-nav {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            margin-top: 1.5rem;
            flex: 1;
        }
        .admin-nav a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 500;
            transition: var(--transition);
        }
        .admin-nav a:hover, .admin-nav a.active {
            background: #1e293b;
            color: #fbbf24;
        }
        .admin-main {
            background: #f8fafc;
            padding: 2rem;
            overflow-y: auto;
        }
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: var(--shadow-sm);
        }
        table.yatri-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.88rem;
            text-align: left;
        }
        table.yatri-table th {
            background: #0f172a;
            color: #ffffff;
            padding: 0.85rem 1rem;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table.yatri-table td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }
        table.yatri-table tr:hover {
            background: #f8fafc;
        }
        @media (max-width: 900px) {
            .admin-layout { grid-template-columns: 1fr; }
            .admin-sidebar { display: none; }
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar Navigation -->
        <aside class="admin-sidebar">
            <div class="brand-wrap" style="padding-bottom:1rem; border-bottom:1px solid #1e293b;">
                <div class="brand-logo-icon">🕉️</div>
                <div class="brand-text">
                    <h2 style="font-size:1.05rem; font-weight:800; color:#ffffff; margin:0;">YATRI CONTROL</h2>
                    <span style="color:#fbbf24; font-size:0.7rem;">Admin Moderation Desk</span>
                </div>
            </div>

            <ul class="admin-nav">
                <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">📊 Overview Statistics</a></li>
                <li><a href="{{ route('admin.requests') }}" class="{{ request()->routeIs('admin.requests*') ? 'active' : '' }}">🚨 Assistance Triage</a></li>
                <li><a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">👥 User & Role Access</a></li>
                <li><a href="{{ route('admin.medical') }}" class="{{ request()->routeIs('admin.medical*') ? 'active' : '' }}">🏥 Medical Directory</a></li>
                <li><a href="{{ route('admin.accommodations') }}" class="{{ request()->routeIs('admin.accommodations*') ? 'active' : '' }}">🏨 Accommodations</a></li>
                <li><a href="{{ route('admin.announcements') }}" class="{{ request()->routeIs('admin.announcements*') ? 'active' : '' }}">📢 Safety Advisories</a></li>
                <li><a href="{{ route('admin.faqs') }}" class="{{ request()->routeIs('admin.faqs*') ? 'active' : '' }}">❓ FAQ Knowledge Base</a></li>
                <li><a href="{{ route('admin.feedback') }}" class="{{ request()->routeIs('admin.feedback*') ? 'active' : '' }}">⭐ Feedback & Reviews</a></li>
                <li><a href="{{ route('admin.audit-logs') }}" class="{{ request()->routeIs('admin.audit-logs*') ? 'active' : '' }}">📜 Security Audit Logs</a></li>
            </ul>

            <div style="padding-top:1rem; border-top:1px solid #1e293b;">
                <div style="font-size:0.75rem; color:#94a3b8; margin-bottom:0.75rem;">
                    Logged as: <strong style="color:#fff;">{{ Auth::user()->name }}</strong>
                </div>
                <div style="display:flex; gap:0.5rem;">
                    <a href="{{ route('home') }}" class="btn btn-sm btn-secondary" style="flex:1; font-size:0.75rem;">Public Site</a>
                    <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-navy" style="font-size:0.75rem; background:#dc2626;">Logout</button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Admin Content Area -->
        <main class="admin-main">
            <!-- Header Bar -->
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem; flex-wrap:wrap; gap:1rem;">
                <div>
                    <h1 style="color:#0a192f; font-size:1.6rem; font-weight:800; margin:0;">@yield('header_title', 'Administrative Overview')</h1>
                    <span style="color:#64748b; font-size:0.85rem;">Platform Governance | SATI Vidisha Capstone Project (Nidhi Chaudhary)</span>
                </div>

                <!-- Export CSV Shortcuts -->
                <div style="display:flex; gap:0.5rem;">
                    <a href="{{ route('admin.export', 'requests') }}" class="btn btn-sm btn-navy">📥 Export Requests CSV</a>
                    <a href="{{ route('admin.export', 'users') }}" class="btn btn-sm btn-secondary">📥 Export Users CSV</a>
                </div>
            </div>

            @if(session('success'))
                <div style="background:#d1fae5; border:1px solid #a7f3d0; color:#065f46; padding:0.85rem 1.25rem; border-radius:8px; margin-bottom:1.5rem; display:flex; justify-content:space-between;">
                    <div>✅ {{ session('success') }}</div>
                    <button onclick="this.parentElement.remove()" style="background:none; border:none; cursor:pointer;">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div style="background:#fee2e2; border:1px solid #fecdd3; color:#991b1b; padding:0.85rem 1.25rem; border-radius:8px; margin-bottom:1.5rem; display:flex; justify-content:space-between;">
                    <div>⚠️ {{ session('error') }}</div>
                    <button onclick="this.parentElement.remove()" style="background:none; border:none; cursor:pointer;">&times;</button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="{{ asset('js/yatri-app.js') }}"></script>
    @stack('scripts')
</body>
</html>
