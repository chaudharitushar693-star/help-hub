@extends('layouts.app')

@section('title', 'Reset Password – Yatri Sahayata')

@section('content')
<section class="section" style="background:#f8fafc; padding:4rem 0;">
    <div class="container" style="max-width:440px;">
        <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:16px; padding:2.5rem; box-shadow:var(--shadow-lg);">
            <div style="text-align:center; margin-bottom:2rem;">
                <div class="brand-logo-icon" style="margin:0 auto 1rem; width:3.5rem; height:3.5rem; font-size:1.75rem;">🔑</div>
                <h1 style="color:#0a192f; font-size:1.5rem; font-weight:800; margin:0;">Reset Account Password</h1>
                <p style="color:#64748b; font-size:0.88rem; margin-top:0.25rem;">Enter your registered email to receive recovery instructions</p>
            </div>

            @if(session('status'))
                <div style="background:#d1fae5; border:1px solid #a7f3d0; color:#065f46; padding:0.85rem 1rem; border-radius:8px; font-size:0.85rem; margin-bottom:1.5rem;">
                    ✅ {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="resetEmail">Email Address</label>
                    <input type="email" id="resetEmail" name="email" class="form-control" placeholder="name@example.com" required autofocus>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; padding:0.75rem;">Send Recovery Instructions</button>
            </form>

            <div style="margin-top:1.5rem; text-align:center; font-size:0.88rem;">
                <a href="{{ route('login') }}" style="color:#0284c7; text-decoration:none;">&larr; Back to Sign In</a>
            </div>
        </div>
    </div>
</section>
@endsection
