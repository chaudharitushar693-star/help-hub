@extends('layouts.app')

@section('title', 'Pilgrim Trip Itinerary – Yatri Sahayata')

@section('content')
<section class="section" style="background:#f8fafc; padding:2.5rem 0;">
    <div class="container" style="max-width:960px;">
        @php
            $data = $isSaved ? [
                'title' => $itinerary->title,
                'circuit' => $itinerary->destination_circuit,
                'duration_days' => $itinerary->duration_days,
                'group_size' => $itinerary->group_size,
                'estimated_budget_inr' => $itinerary->estimated_budget_inr,
                'travel_mode' => $itinerary->travel_mode,
                'days' => []
            ] : $itineraryData;

            if ($isSaved) {
                // Group items by day
                $grouped = $itinerary->items->groupBy('day_number');
                foreach($grouped as $dayNum => $items) {
                    $dayItems = [];
                    foreach($items as $item) {
                        $dayItems[] = [
                            'time' => $item->time_slot,
                            'title' => $item->activity_title,
                            'desc' => $item->activity_description,
                            'location' => $item->location,
                            'cost' => $item->estimated_cost,
                            'tips' => $item->tips,
                        ];
                    }
                    $data['days'][] = [
                        'day' => $dayNum,
                        'theme' => "Day {$dayNum} Sacred Schedule",
                        'items' => $dayItems
                    ];
                }
            }
        @endphp

        <!-- Actions Bar -->
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
            <a href="{{ route('planner.index') }}" class="btn btn-sm btn-secondary">&larr; Back to Planner</a>
            <div style="display:flex; gap:0.5rem;">
                <button onclick="window.print()" class="btn btn-sm btn-navy">🖨️ Print / Save as PDF</button>
                @if(!$isSaved && Auth::check())
                    <button id="saveItineraryBtn" onclick="saveCurrentItinerary()" class="btn btn-sm btn-primary">💾 Save to My Profile</button>
                @elseif(!$isSaved)
                    <a href="{{ route('login') }}" class="btn btn-sm btn-primary">Sign in to Save Plan</a>
                @endif
            </div>
        </div>

        <!-- Main Printable Itinerary Card -->
        <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:16px; padding:2.5rem; box-shadow:var(--shadow-md);" id="printableArea">
            <div style="border-bottom:2px solid #f1f5f9; padding-bottom:1.5rem; margin-bottom:2rem; display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem;">
                <div>
                    <span class="card-badge badge-gold">Verified Algorithmic Plan</span>
                    <h1 style="color:#0a192f; font-size:1.8rem; font-weight:800; margin:0.4rem 0 0.2rem;">{{ $data['title'] }}</h1>
                    <div style="font-size:0.9rem; color:#64748b;">
                        Circuit: <strong>{{ $data['circuit'] }}</strong> | Group: <strong>{{ $data['group_size'] }} Yatris</strong> | Duration: <strong>{{ $data['duration_days'] }} Days</strong>
                    </div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:0.8rem; color:#64748b; text-transform:uppercase;">Estimated Total Budget</div>
                    <div style="font-size:1.8rem; font-weight:800; color:#10b981;">₹{{ number_format($data['estimated_budget_inr']) }}*</div>
                    <span style="font-size:0.72rem; color:#94a3b8;">*Estimated expenses; subject to personal tariff</span>
                </div>
            </div>

            <!-- Day by Day Timetable -->
            @foreach($data['days'] as $day)
            <div style="margin-bottom:2.5rem;">
                <div style="background:#0a192f; color:#ffffff; padding:0.75rem 1.25rem; border-radius:8px; display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
                    <h3 style="font-size:1.15rem; font-weight:700; margin:0;">Day {{ $day['day'] }} — {{ $day['theme'] }}</h3>
                    <span style="font-size:0.8rem; color:#fbbf24;">Sacred Schedule</span>
                </div>

                <div class="route-timeline" style="margin-left:0.5rem;">
                    @foreach($day['items'] as $item)
                    <div class="timeline-step">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:0.35rem; flex-wrap:wrap; gap:0.5rem;">
                                <div>
                                    <span style="font-size:0.78rem; font-weight:700; color:#0284c7; background:#e0f2fe; padding:2px 8px; border-radius:4px;">{{ $item['time'] }}</span>
                                    <h4 style="color:#0a192f; font-size:1.1rem; font-weight:800; margin:0.35rem 0 0.15rem;">{{ $item['title'] }}</h4>
                                    <div style="font-size:0.82rem; color:#64748b;">📍 {{ $item['location'] }}</div>
                                </div>
                                @if(isset($item['cost']) && $item['cost'] > 0)
                                    <span style="font-size:0.85rem; font-weight:700; color:#059669; background:#d1fae5; padding:2px 8px; border-radius:4px;">₹{{ number_format($item['cost']) }}</span>
                                @endif
                            </div>

                            <p style="font-size:0.92rem; color:#475569; line-height:1.5; margin:0.5rem 0;">{{ $item['desc'] }}</p>

                            @if(!empty($item['tips']))
                            <div style="background:#fffbeb; border:1px solid #fef3c7; border-radius:6px; padding:0.5rem 0.75rem; font-size:0.82rem; color:#92400e; margin-top:0.5rem;">
                                💡 <strong>Pilgrim Tip:</strong> {{ $item['tips'] }}
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            <!-- Estimated Information Disclaimer -->
            <div style="margin-top:2rem; padding:1.25rem; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; font-size:0.82rem; color:#64748b; line-height:1.5;">
                <strong>Disclaimer & Guidance:</strong> Timings, queue wait periods, and expense calculations are algorithmic approximations designed for logistical planning. Weather fluctuations, temple crowd spikes, and ceremonial VIP pauses may alter schedules. Follow ground instructions issued by Shrine Board authorities.
            </div>
        </div>
    </div>
</section>

@if(!$isSaved)
<script>
function saveCurrentItinerary() {
    const btn = document.getElementById('saveItineraryBtn');
    btn.disabled = true;
    btn.innerText = 'Saving Plan...';

    const rawData = @json($data);
    const flatItems = [];

    rawData.days.forEach(d => {
        d.items.forEach(it => {
            flatItems.push({
                day_number: d.day,
                time_slot: it.time,
                activity_title: it.title,
                activity_description: it.desc,
                location: it.location,
                estimated_cost: it.cost || 0,
                tips: it.tips || null
            });
        });
    });

    const payload = {
        title: rawData.title,
        destination_circuit: rawData.circuit,
        start_date: '{{ request()->input("start_date", date("Y-m-d", strtotime("+3 days"))) }}',
        duration_days: rawData.duration_days,
        group_size: rawData.group_size,
        estimated_budget_inr: rawData.estimated_budget_inr,
        travel_mode: rawData.travel_mode || 'train',
        accessibility_needs: '{{ request()->input("accessibility", "standard") }}',
        items: flatItems
    };

    fetch('{{ route("user.planner.save") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('Itinerary saved to your pilgrimage dashboard!', 'success');
            if (data.redirect_url) window.location.href = data.redirect_url;
        } else {
            showToast(data.message || 'Error saving itinerary', 'error');
            btn.disabled = false;
            btn.innerText = '💾 Save to My Profile';
        }
    })
    .catch(() => {
        showToast('Network error while saving itinerary.', 'error');
        btn.disabled = false;
        btn.innerText = '💾 Save to My Profile';
    });
}
</script>
@endif
@endsection
