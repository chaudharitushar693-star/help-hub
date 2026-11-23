@extends('layouts.app')

@section('title', 'Frequently Asked Questions – Yatri Sahayata')

@section('content')
<section class="section" style="background:#ffffff; padding-top:2.5rem;">
    <div class="container" style="max-width:960px;">
        <div class="section-header">
            <span class="section-tag">Help Desk Knowledge Base</span>
            <h1 class="section-title">Frequently Asked Questions (FAQs)</h1>
            <p class="section-desc">Practical guidance in English and हिन्दी covering registrations, senior citizens, lockers, and emergency protocols.</p>
        </div>

        <!-- Category Filter Tabs -->
        <div style="display:flex; flex-wrap:wrap; gap:0.5rem; justify-content:center; margin-bottom:2.5rem;">
            <a href="{{ route('faqs') }}" class="btn btn-sm {{ empty($category) ? 'btn-primary' : 'btn-secondary' }}">All Questions</a>
            @foreach($categories as $cat)
                <a href="{{ route('faqs') }}?category={{ urlencode($cat) }}" class="btn btn-sm {{ $category === $cat ? 'btn-primary' : 'btn-secondary' }}">{{ $cat }}</a>
            @endforeach
        </div>

        <div class="faq-wrap">
            @forelse($faqs as $faq)
            <div class="faq-item">
                <button type="button" class="faq-question">
                    <span>{{ $faq->question }}</span>
                    <span class="toggle-icon">+</span>
                </button>
                <div class="faq-answer" style="display:none;">
                    <p style="margin-bottom:0.75rem;">{{ $faq->answer }}</p>
                    @if($faq->hindi_answer)
                    <div class="faq-hindi">
                        <strong>हिन्दी अनुवाद:</strong><br>
                        {{ $faq->hindi_answer }}
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div style="padding:3rem; text-align:center; color:#64748b; background:#f8fafc; border-radius:12px;">
                No FAQs found in this category.
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
