@extends('layouts.app')

@section('title', 'Pilgrim Dashboard – Yatri Sahayata')

@section('content')
<section class="section" style="background:#f8fafc; padding:2.5rem 0;">
    <div class="container">
        <!-- Dashboard Welcome Banner -->
        <div style="background:linear-gradient(135deg, #0a192f, #1e293b); color:#ffffff; border-radius:16px; padding:2rem; margin-bottom:2rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1.5rem; box-shadow:var(--shadow-lg);">
            <div style="display:flex; align-items:center; gap:1.25rem;">
                <div style="width:64px; height:64px; border-radius:50%; background:#f59e0b; color:#0a192f; display:flex; align-items:center; justify-content:center; font-size:2rem; font-weight:800;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <span style="font-size:0.8rem; background:rgba(245,158,11,0.2); color:#fbbf24; border:1px solid rgba(245,158,11,0.4); padding:2px 10px; border-radius:12px; font-weight:700;">
                        PILGRIM MEMBER
                    </span>
                    <h1 style="font-size:1.75rem; font-weight:800; margin:0.35rem 0 0.15rem;">{{ $user->name }}</h1>
                    <div style="font-size:0.85rem; color:#cbd5e1;">{{ $user->email }} | {{ $user->phone }}</div>
                </div>
            </div>

            <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
                <a href="{{ route('planner.index') }}" class="btn btn-primary">✨ New Yatra Plan</a>
                <a href="{{ route('user.profile') }}" class="btn btn-secondary">Edit Emergency Profile</a>
            </div>
        </div>

        <!-- Quick Metrics Cards -->
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:1.25rem; margin-bottom:2.5rem;">
            <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; padding:1.25rem; box-shadow:var(--shadow-sm);">
                <div style="font-size:0.8rem; color:#64748b; text-transform:uppercase; font-weight:700;">Saved Itineraries</div>
                <div style="font-size:2rem; font-weight:800; color:#0a192f; margin:0.25rem 0;">{{ $itineraries->count() }}</div>
                <a href="{{ route('planner.index') }}" style="font-size:0.8rem; color:#f59e0b; text-decoration:none;">View Itineraries &rarr;</a>
            </div>

            <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; padding:1.25rem; box-shadow:var(--shadow-sm);">
                <div style="font-size:0.8rem; color:#64748b; text-transform:uppercase; font-weight:700;">Assistance Cases</div>
                <div style="font-size:2rem; font-weight:800; color:#0a192f; margin:0.25rem 0;">{{ $requests->count() }}</div>
                <a href="{{ route('user.requests') }}" style="font-size:0.8rem; color:#f59e0b; text-decoration:none;">Track Timeline &rarr;</a>
            </div>

            <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; padding:1.25rem; box-shadow:var(--shadow-sm);">
                <div style="font-size:0.8rem; color:#64748b; text-transform:uppercase; font-weight:700;">Saved Places / POIs</div>
                <div style="font-size:2rem; font-weight:800; color:#0a192f; margin:0.25rem 0;">{{ $savedPlacesCount }}</div>
                <a href="{{ route('user.saved-places') }}" style="font-size:0.8rem; color:#f59e0b; text-decoration:none;">View Saved Places &rarr;</a>
            </div>

            <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; padding:1.25rem; box-shadow:var(--shadow-sm);">
                <div style="font-size:0.8rem; color:#64748b; text-transform:uppercase; font-weight:700;">Notifications</div>
                <div style="font-size:2rem; font-weight:800; color:#0a192f; margin:0.25rem 0;">{{ $notifications->count() }}</div>
                <a href="{{ route('user.notifications') }}" style="font-size:0.8rem; color:#f59e0b; text-decoration:none;">Open Alerts &rarr;</a>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1.2fr 0.8fr; gap:2rem;">
            <!-- Left: Active Assistance Requests -->
            <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; padding:1.75rem;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
                    <h3 style="color:#0a192f; font-size:1.25rem; font-weight:800; margin:0;">🚨 My Assistance Requests</h3>
                    <a href="{{ route('user.requests') }}" style="font-size:0.85rem; color:#0284c7; text-decoration:none;">View All</a>
                </div>

                @forelse($requests as $req)
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:1rem; margin-bottom:0.75rem; display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <div style="display:flex; align-items:center; gap:0.5rem;">
                            <strong style="color:#0a192f;">{{ $req->tracking_code }}</strong>
                            <span class="card-badge {{ $req->status === 'resolved' ? 'badge-verified' : 'badge-emergency' }}" style="margin:0; font-size:0.7rem;">
                                {{ strtoupper($req->status) }}
                            </span>
                        </div>
                        <div style="font-size:0.82rem; color:#64748b; margin-top:2px;">
                            {{ $req->circuit }} | {{ Str::limit($req->description, 50) }}
                        </div>
                    </div>
                    <a href="{{ route('assistance.track', $req->tracking_code) }}" class="btn btn-sm btn-secondary">Track</a>
                </div>
                @empty
                <div style="padding:2rem; text-align:center; color:#64748b; font-size:0.9rem;">
                    No assistance cases logged. If you ever need help on the track, tap the <strong>SOS Button</strong>.
                </div>
                @endforelse
            </div>

            <!-- Right: Feedback Form -->
            <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; padding:1.75rem;">
                <h3 style="color:#0a192f; font-size:1.25rem; font-weight:800; margin-bottom:0.5rem;">⭐ Pilgrim Feedback & Review</h3>
                <p style="font-size:0.85rem; color:#64748b; margin-bottom:1.25rem;">Share your track experience to help fellow pilgrims and coordinators improve ground facilities.</p>

                <form action="{{ route('user.feedback.submit') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Pilgrimage Circuit</label>
                        <select name="circuit" class="form-control" required>
                            <option value="Vaishno Devi">Vaishno Devi</option>
                            <option value="Ujjain Mahakal">Ujjain Mahakaleshwar</option>
                            <option value="Char Dham">Char Dham</option>
                            <option value="Omkareshwar">Omkareshwar</option>
                            <option value="Varanasi Kashi">Kashi Vishwanath</option>
                        </select>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                        <div class="form-group">
                            <label class="form-label">Rating (1 to 5 Stars)</label>
                            <select name="rating" class="form-control" required>
                                <option value="5">⭐⭐⭐⭐⭐ (5 Stars)</option>
                                <option value="4">⭐⭐⭐⭐ (4 Stars)</option>
                                <option value="3">⭐⭐⭐ (3 Stars)</option>
                                <option value="2">⭐⭐ (2 Stars)</option>
                                <option value="1">⭐ (1 Star)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Key Service Aspect</label>
                            <select name="service_aspect" class="form-control" required>
                                <option value="Route Guide">Route & Milestones</option>
                                <option value="Medical Assistance">Medical Emergency Desk</option>
                                <option value="Smart Planner">Smart Itinerary Planner</option>
                                <option value="Cleanliness">Cleanliness & Rest Sheds</option>
                                <option value="Overall Experience">Overall Experience</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Your Comments & Experience</label>
                        <textarea name="comment" rows="3" class="form-control" placeholder="Share how Yatri Sahayata helped your pilgrimage..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;">Submit Pilgrim Review</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
