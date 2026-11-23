@extends('layouts.app')

@section('title', 'Smart Yatra Planner – Intelligent Itinerary Generator')

@section('content')
<section class="section" style="background:#f8fafc; padding:2.5rem 0;">
    <div class="container" style="max-width:960px;">
        <div class="section-header">
            <span class="section-tag">Algorithmic Travel Optimization</span>
            <h1 class="section-title">Smart Yatra Planner</h1>
            <p class="section-desc">Generate a personalized, time-optimized pilgrimage schedule factoring in group size, senior citizen pacing, battery cars, and budget estimates.</p>
        </div>

        <!-- Planner Form -->
        <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:16px; padding:2.5rem; box-shadow:var(--shadow-md); margin-bottom:3rem;">
            <form id="smartPlannerForm" action="{{ route('planner.generate') }}" method="POST">
                @csrf
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                    <div class="form-group">
                        <label class="form-label" for="planCircuit">Choose Holy Pilgrimage Circuit *</label>
                        <select id="planCircuit" name="circuit" class="form-control" required>
                            <option value="Vaishno Devi">Shri Mata Vaishno Devi (Katra, J&K)</option>
                            <option value="Ujjain Mahakal">Shri Mahakaleshwar & Omkareshwar (MP)</option>
                            <option value="Char Dham">Char Dham (Kedarnath / Badrinath)</option>
                            <option value="Varanasi Kashi">Kashi Vishwanath (Varanasi, UP)</option>
                            <option value="Omkareshwar">Omkareshwar Jyotirlinga (MP)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="planStartDate">Planned Departure / Arrival Date *</label>
                        <input type="date" id="planStartDate" name="start_date" class="form-control" value="{{ date('Y-m-d', strtotime('+3 days')) }}" required>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:1.25rem;">
                    <div class="form-group">
                        <label class="form-label" for="planDays">Duration (Days) *</label>
                        <select id="planDays" name="duration_days" class="form-control" required>
                            <option value="1">1 Day (Express Darshan)</option>
                            <option value="2">2 Days (Standard)</option>
                            <option value="3" selected>3 Days (Recommended with Elders)</option>
                            <option value="4">4 Days (Relaxed)</option>
                            <option value="5">5 Days (Complete Circuit)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="planGroup">Total Group Size *</label>
                        <input type="number" id="planGroup" name="group_size" class="form-control" min="1" max="50" value="2" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="planTravelMode">Travel Arrival Mode *</label>
                        <select id="planTravelMode" name="travel_mode" class="form-control" required>
                            <option value="train">Train / Railway</option>
                            <option value="bus">Interstate Bus</option>
                            <option value="flight">Flight / Airport Transit</option>
                            <option value="own_vehicle">Personal Vehicle / Taxi</option>
                        </select>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                    <div class="form-group">
                        <label class="form-label" for="planAccessibility">Accessibility & Senior Citizen Needs</label>
                        <select id="planAccessibility" name="accessibility" class="form-control">
                            <option value="standard">Standard Trekking Pace</option>
                            <option value="elderly">Elderly Friendly (Frequent Rest & Gentler Gradients)</option>
                            <option value="wheelchair">Wheelchair & Battery Car Priority</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="planBudget">Estimated Total Budget (INR ₹)</label>
                        <input type="number" id="planBudget" name="budget" class="form-control" placeholder="Optional (e.g. 10000)" min="1000" step="500">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; font-size:1.05rem; padding:0.85rem; margin-top:1rem;">
                    ✨ Generate Smart Pilgrim Itinerary
                </button>
            </form>
        </div>

        <!-- Saved Itineraries Section -->
        @if(isset($savedItineraries) && $savedItineraries->count() > 0)
        <div>
            <h3 style="color:#0a192f; font-size:1.3rem; font-weight:800; margin-bottom:1rem;">📁 My Saved Pilgrimage Plans</h3>
            <div class="cards-grid">
                @foreach($savedItineraries as $itn)
                <div class="yatri-card">
                    <span class="card-badge badge-gold">{{ $itn->duration_days }} Days Plan</span>
                    <h4 class="card-title">{{ $itn->title }}</h4>
                    <div class="card-meta">
                        <div>🚩 Circuit: <strong>{{ $itn->destination_circuit }}</strong></div>
                        <div>📅 Date: {{ \Carbon\Carbon::parse($itn->start_date)->format('d M Y') }}</div>
                        <div>👥 Group: {{ $itn->group_size }} Pilgrims</div>
                        <div>💰 Budget: ₹{{ number_format($itn->estimated_budget_inr) }} (Estimated)</div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('planner.show', $itn->id) }}" class="btn btn-sm btn-primary">Open & Print</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
