@extends('layouts.app')

@section('title', 'Contact Coordination Desk – Yatri Sahayata')

@section('content')
<section class="section" style="background:#ffffff; padding-top:2.5rem;">
    <div class="container" style="max-width:960px;">
        <div class="section-header">
            <span class="section-tag">Communication & Feedback</span>
            <h1 class="section-title">Contact Coordination Desk</h1>
            <p class="section-desc">Have a technical query, ground data discrepancy, or suggestion for the student development team?</p>
        </div>

        <div style="display:grid; grid-template-columns:1.2fr 0.8fr; gap:2.5rem;">
            <!-- Contact Form -->
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:2rem;">
                <h3 style="color:#0a192f; font-size:1.25rem; font-weight:800; margin-bottom:1.25rem;">Send Message to Project Desk</h3>

                <form action="{{ route('contact.submit') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="contactName">Your Full Name *</label>
                        <input type="text" id="contactName" name="name" class="form-control" placeholder="Ramesh Sharma" required value="{{ Auth::check() ? Auth::user()->name : '' }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="contactEmail">Email Address *</label>
                        <input type="email" id="contactEmail" name="email" class="form-control" placeholder="ramesh@example.com" required value="{{ Auth::check() ? Auth::user()->email : '' }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="contactSubject">Inquiry Subject *</label>
                        <input type="text" id="contactSubject" name="subject" class="form-control" placeholder="e.g. Route information update or feedback" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="contactMessage">Detailed Message *</label>
                        <textarea id="contactMessage" name="message" rows="4" class="form-control" placeholder="Write your inquiry or feedback..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;">Dispatch Message</button>
                </form>
            </div>

            <!-- College & Academic Attribution Card -->
            <div>
                <div style="background:#0a192f; color:#ffffff; border-radius:12px; padding:2rem; margin-bottom:1.5rem;">
                    <div style="font-size:2rem; margin-bottom:0.5rem;">🎓</div>
                    <h3 style="color:#fbbf24; font-size:1.2rem; font-weight:800; margin-bottom:0.75rem;">Developer & Academic Guide</h3>
                    <div style="font-size:0.88rem; color:#cbd5e1; line-height:1.6;">
                        <strong>Lead Developer:</strong> Nidhi Chaudhary<br>
                        <strong>Enrollment No:</strong> 0108CS241082<br>
                        <strong>College:</strong> Samrat Ashok Technological Institute (SATI)<br>
                        <strong>Location:</strong> Vidisha, Madhya Pradesh - 464001<br>
                        <strong>Department:</strong> Computer Science & Engineering
                    </div>
                </div>

                <div style="background:#fffbeb; border:1px solid #fef3c7; border-radius:12px; padding:1.5rem;">
                    <h4 style="color:#92400e; font-size:0.95rem; font-weight:700; margin-bottom:0.5rem;">⚠️ Operational Emergency Notice</h4>
                    <p style="font-size:0.82rem; color:#78350f; margin:0; line-height:1.5;">
                        This contact form is for general inquiries and academic evaluation. For acute ground emergencies, use the <strong>SOS Button</strong> or dial <strong>112 / 108</strong> directly.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
