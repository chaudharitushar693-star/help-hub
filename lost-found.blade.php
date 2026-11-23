@extends('layouts.app')

@section('title', 'Lost & Found Community Reporting Board – Yatri Sahayata')

@section('content')
<section class="section" style="background:#ffffff; padding-top:2.5rem;">
    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem; flex-wrap:wrap; gap:1rem;">
            <div>
                <span class="section-tag">Community Mutual Aid</span>
                <h1 class="section-title" style="margin-bottom:0.25rem;">Lost & Found Pilgrim Board</h1>
                <p style="color:#64748b; margin:0;">Report lost documents, baggage, or found articles to help fellow yatris reunite with their belongings.</p>
            </div>
            <button onclick="document.getElementById('reportModal').style.display='flex'" class="btn btn-primary">➕ Report Lost or Found Item</button>
        </div>

        <div class="cards-grid">
            @forelse($reports as $r)
            <div class="yatri-card">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <span class="card-badge {{ $r->report_type === 'lost' ? 'badge-emergency' : 'badge-verified' }}">
                        {{ $r->report_type === 'lost' ? '🔍 LOST ITEM' : '🎁 FOUND ITEM' }}
                    </span>
                    <span style="font-size:0.75rem; color:#64748b;">
                        {{ \Carbon\Carbon::parse($r->incident_date)->format('d M Y') }}
                    </span>
                </div>

                <h3 class="card-title">{{ $r->item_name }}</h3>
                <div class="card-meta">
                    <div class="card-meta-item">📁 Category: <strong>{{ $r->category }}</strong></div>
                    <div class="card-meta-item">📍 Location: {{ $r->incident_location }} (<strong>{{ $r->circuit }}</strong>)</div>
                    <div class="card-meta-item">👤 Contact Person: {{ $r->contact_person }}</div>
                </div>

                <p class="card-desc">{{ $r->description }}</p>

                <div class="card-footer">
                    <span style="font-size:0.8rem; background:#f1f5f9; padding:2px 8px; border-radius:12px; color:#475569;">
                        Status: {{ ucfirst($r->status) }}
                    </span>
                    <a href="tel:{{ $r->contact_phone }}" class="btn btn-sm btn-navy">📞 Call Contact</a>
                </div>
            </div>
            @empty
            <div style="grid-column:1/-1; padding:3rem; text-align:center; color:#64748b; background:#f8fafc; border-radius:12px;">
                No active lost or found reports currently recorded.
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Report Submission Modal -->
<div id="reportModal" class="modal-backdrop">
    <div class="modal-content">
        <button type="button" onclick="document.getElementById('reportModal').style.display='none'" class="modal-close">&times;</button>
        <h3 style="color:#0a192f; font-size:1.3rem; font-weight:800; margin-bottom:1rem;">Report Lost or Found Article</h3>

        <form action="{{ route('lost-found.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Report Type *</label>
                <select name="report_type" class="form-control" required>
                    <option value="lost">I Lost Something</option>
                    <option value="found">I Found Something</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Item / Article Name *</label>
                <input type="text" name="item_name" class="form-control" placeholder="e.g. Black leather wallet with Aadhaar card" required>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div class="form-group">
                    <label class="form-label">Category *</label>
                    <select name="category" class="form-control" required>
                        <option value="Documents/ID Card">Documents / ID Card</option>
                        <option value="Bag/Luggage">Bag / Luggage</option>
                        <option value="Electronics/Phone">Electronics / Phone</option>
                        <option value="Jewellery">Jewellery</option>
                        <option value="Person/Child">Missing Person / Child</option>
                        <option value="Other">Other Article</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Pilgrim Circuit *</label>
                    <select name="circuit" class="form-control" required>
                        <option value="Vaishno Devi">Vaishno Devi</option>
                        <option value="Ujjain Mahakal">Ujjain Mahakal</option>
                        <option value="Char Dham">Char Dham</option>
                        <option value="Omkareshwar">Omkareshwar</option>
                        <option value="Varanasi Kashi">Kashi Vishwanath</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Incident Location & Date *</label>
                <div style="display:grid; grid-template-columns:1.5fr 1fr; gap:1rem;">
                    <input type="text" name="incident_location" class="form-control" placeholder="e.g. Near Banganga Cloakroom Counter 2" required>
                    <input type="date" name="incident_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description & Identifying Marks *</label>
                <textarea name="description" rows="3" class="form-control" placeholder="Provide distinct colors, brand, or details to verify ownership without exposing sensitive numbers..." required></textarea>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div class="form-group">
                    <label class="form-label">Your Name *</label>
                    <input type="text" name="contact_person" class="form-control" required value="{{ Auth::check() ? Auth::user()->name : '' }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Active Contact Phone *</label>
                    <input type="tel" name="contact_phone" class="form-control" required value="{{ Auth::check() ? Auth::user()->phone : '' }}">
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem; margin-top:1.5rem;">
                <button type="button" onclick="document.getElementById('reportModal').style.display='none'" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Publish Community Report</button>
            </div>
        </form>
    </div>
</div>
@endsection
