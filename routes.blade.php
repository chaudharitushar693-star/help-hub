@extends('layouts.app')

@section('title', 'Pilgrim Routes & Interactive Map – ' . $circuit)

@section('content')
<section class="section" style="background:#ffffff; padding-top:2.5rem;">
    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:1rem; margin-bottom:2rem;">
            <div>
                <span class="section-tag">Interactive Navigation & POI Directory</span>
                <h1 class="section-title" style="margin-bottom:0.25rem;">{{ $circuit }} Route Map & Guide</h1>
                <p style="color:#64748b; margin:0;">Verified checkpoints, medical units, water stations, and resting camps.</p>
            </div>

            <!-- Circuit Switcher -->
            <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                <a href="{{ route('routes') }}?circuit=Vaishno+Devi" class="btn btn-sm {{ $circuit === 'Vaishno Devi' ? 'btn-primary' : 'btn-secondary' }}">Vaishno Devi</a>
                <a href="{{ route('routes') }}?circuit=Ujjain+Mahakal" class="btn btn-sm {{ $circuit === 'Ujjain Mahakal' ? 'btn-primary' : 'btn-secondary' }}">Ujjain Mahakal</a>
                <a href="{{ route('routes') }}?circuit=Char+Dham" class="btn btn-sm {{ $circuit === 'Char Dham' ? 'btn-primary' : 'btn-secondary' }}">Char Dham</a>
                <a href="{{ route('routes') }}?circuit=Omkareshwar" class="btn btn-sm {{ $circuit === 'Omkareshwar' ? 'btn-primary' : 'btn-secondary' }}">Omkareshwar</a>
                <a href="{{ route('routes') }}?circuit=Varanasi+Kashi" class="btn btn-sm {{ $circuit === 'Varanasi Kashi' ? 'btn-primary' : 'btn-secondary' }}">Kashi Vishwanath</a>
            </div>
        </div>

        <!-- Interactive Map Container -->
        <div style="background:#f8fafc; border:1px solid #cbd5e1; border-radius:12px; overflow:hidden; box-shadow:var(--shadow-md); margin-bottom:2.5rem;">
            <div style="padding:0.75rem 1.25rem; background:#0a192f; color:#fff; display:flex; justify-content:space-between; align-items:center;">
                <div style="display:flex; align-items:center; gap:0.5rem; font-size:0.88rem;">
                    <span>🗺️</span> <strong>Interactive OpenStreetMap (Leaflet Engine)</strong>
                    <span style="font-size:0.75rem; color:#fbbf24; margin-left:0.5rem;">*Dotted lines represent approximate verified trek pathways</span>
                </div>
                <div style="display:flex; gap:1rem; font-size:0.75rem;">
                    <span style="display:flex; align-items:center; gap:0.3rem;"><span style="color:#ef4444;">🏥</span> Medical Aid Post</span>
                    <span style="display:flex; align-items:center; gap:0.3rem;"><span style="color:#0284c7;">ℹ️</span> Rest / Locker</span>
                    <span style="display:flex; align-items:center; gap:0.3rem;"><span style="color:#f59e0b;">🏨</span> Verified Stay</span>
                </div>
            </div>
            <div id="routeMapMain" style="height:500px; width:100%;"></div>
        </div>

        <!-- Two Column Breakdown -->
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:2rem;">
            <div>
                <h3 style="color:#0a192f; font-size:1.3rem; font-weight:800; margin-bottom:1rem;">📍 Verified Route Facilities & Milestones</h3>
                <div style="display:flex; flex-direction:column; gap:1rem;">
                    @forelse($facilities as $f)
                    <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:8px; padding:1.25rem;">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:0.4rem;">
                            <strong style="color:#0a192f; font-size:1.05rem;">{{ $f->name }}</strong>
                            <span class="card-badge badge-info">{{ $f->category }}</span>
                        </div>
                        <div style="font-size:0.85rem; color:#64748b; margin-bottom:0.5rem;">
                            📍 {{ $f->location }} | ⏱️ {{ $f->operating_hours }}
                        </div>
                        <p style="font-size:0.88rem; color:#475569; margin:0;">{{ $f->description }}</p>
                    </div>
                    @empty
                    <div style="padding:2rem; text-align:center; color:#64748b; background:#f8fafc; border-radius:8px;">
                        No facilities currently listed for {{ $circuit }}.
                    </div>
                    @endforelse
                </div>
            </div>

            <div>
                <h3 style="color:#991b1b; font-size:1.3rem; font-weight:800; margin-bottom:1rem;">🏥 Medical Units & Oxygen Counters Along Path</h3>
                <div style="display:flex; flex-direction:column; gap:1rem;">
                    @forelse($medicalCentres as $m)
                    <div style="background:#ffffff; border:1px solid #fee2e2; border-left:4px solid #ef4444; border-radius:8px; padding:1.25rem;">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:0.4rem;">
                            <strong style="color:#991b1b; font-size:1.05rem;">{{ $m->name }}</strong>
                            <span class="card-badge badge-emergency">{{ $m->centre_type }}</span>
                        </div>
                        <div style="font-size:0.85rem; color:#64748b; margin-bottom:0.5rem;">
                            📍 {{ $m->location }} | 👨‍⚕️ {{ $m->doctor_in_charge ?? 'Duty Medical Staff' }}
                        </div>
                        <div style="background:#fef2f2; padding:0.5rem 0.75rem; border-radius:6px; font-size:0.85rem; color:#b91c1c; font-weight:700;">
                            📞 Emergency Hotline: <a href="tel:{{ $m->helpline_number }}" style="color:#b91c1c;">{{ $m->helpline_number }}</a>
                        </div>
                    </div>
                    @empty
                    <div style="padding:2rem; text-align:center; color:#64748b; background:#f8fafc; border-radius:8px;">
                        No specific medical posts registered for this sub-circuit. Dial 112 / 108.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    initPilgrimMap('routeMapMain', "{{ $circuit }}");
});
</script>
@endpush
