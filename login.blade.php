@extends('layouts.app')

@section('title', 'Sign In – Yatri Sahayata Pilgrim Portal')

@section('content')
<section class="section" style="background:#f8fafc; padding:3.5rem 0;">
    <div class="container" style="max-width:480px;">
        <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:16px; padding:2.5rem; box-shadow:var(--shadow-lg);">
            <div style="text-align:center; margin-bottom:2rem;">
                <div class="brand-logo-icon" style="margin:0 auto 1rem; width:3.5rem; height:3.5rem; font-size:1.75rem;">🕉️</div>
                <h1 style="color:#0a192f; font-size:1.6rem; font-weight:800; margin:0;">Sign In to Your Account</h1>
                <p style="color:#64748b; font-size:0.88rem; margin-top:0.25rem;">Access your saved itineraries, places & assistance cases</p>
            </div>

            @if($errors->any())
                <div style="background:#fee2e2; border:1px solid #fecdd3; color:#991b1b; padding:0.75rem 1rem; border-radius:8px; font-size:0.85rem; margin-bottom:1.5rem;">
                    @foreach($errors->all() as $err)
                        <div>⚠️ {{ $err }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="loginEmail">Email Address</label>
                    <input type="email" id="loginEmail" name="email" class="form-control" placeholder="name@example.com" value="{{ old('email') }}" required autofocus autocomplete="email">
                </div>

                <div class="form-group">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.4rem;">
                        <label class="form-label" for="loginPassword" style="margin:0;">Password</label>
                        <a href="{{ route('password.request') }}" style="font-size:0.8rem; color:#f59e0b; text-decoration:none;">Forgot Password?</a>
                    </div>
                    <div style="position:relative;">
                        <input type="password" id="loginPassword" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
                        <button type="button" id="togglePasswordBtn" onclick="togglePasswordVisibility()" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#64748b; font-size:1rem;" title="Show or hide password">👁️</button>
                    </div>
                </div>

                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
                    <label style="display:flex; align-items:center; gap:0.4rem; font-size:0.85rem; color:#475569; cursor:pointer;">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember this device
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; font-size:1rem; padding:0.75rem;">Sign In</button>
            </form>

            <div style="margin-top:1.5rem; text-align:center; font-size:0.88rem; color:#64748b;">
                New pilgrim? <a href="{{ route('register') }}" style="color:#0284c7; font-weight:600; text-decoration:none;">Create Free Account</a>
            </div>

            <!-- Demo Pre-Seeded Accounts Helper -->
            <div style="margin-top:2rem; padding-top:1.5rem; border-top:1px dashed #cbd5e1; font-size:0.78rem; color:#475569;">
                <strong>🔑 Quick Demo Accounts (Click to Fill):</strong>
                <div style="display:flex; flex-direction:column; gap:0.4rem; margin-top:0.5rem;">
                    <button type="button" onclick="fillCreds('admin@yatrisahayata.org', 'Admin@123')" style="text-align:left; background:#f1f5f9; border:1px solid #cbd5e1; padding:4px 8px; border-radius:4px; cursor:pointer;">
                        🛡️ <strong>Admin:</strong> <code>admin@yatrisahayata.org</code> | <code>Admin@123</code>
                    </button>
                    <button type="button" onclick="fillCreds('ramesh.sharma@example.com', 'Yatri@123')" style="text-align:left; background:#f1f5f9; border:1px solid #cbd5e1; padding:4px 8px; border-radius:4px; cursor:pointer;">
                        👤 <strong>User (Pilgrim):</strong> <code>ramesh.sharma@example.com</code> | <code>Yatri@123</code>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function togglePasswordVisibility() {
    const input = document.getElementById('loginPassword');
    const btn = document.getElementById('togglePasswordBtn');
    if (input.type === 'password') {
        input.type = 'text';
        btn.innerText = '🙈';
    } else {
        input.type = 'password';
        btn.innerText = '👁️';
    }
}

function fillCreds(email, pass) {
    document.getElementById('loginEmail').value = email;
    document.getElementById('loginPassword').value = pass;
}
</script>
@endsection
