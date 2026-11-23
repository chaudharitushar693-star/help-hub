<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="YATRI SAHAYATA – Smart Pilgrim Assistance Platform providing route guidance, emergency medical directory, RFID registration rules, and yatra itinerary planning. Developed by Nidhi Chaudhary (0108CS241082), SATI Vidisha.">
    <meta name="author" content="Nidhi Chaudhary (0108CS241082), Samrat Ashok Technological Institute, Vidisha, MP">
    <title>@yield('title', 'YATRI SAHAYATA – Smart Pilgrim Assistance Platform')</title>

    <!-- Leaflet CSS for Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    <!-- Yatri Custom Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/yatri-main.css') }}">
    @stack('styles')
</head>
<body>
    <!-- Official Academic Attribution & Independent Project Disclaimer -->
    @include('components.disclaimer')

    <!-- Responsive Navigation Header -->
    @include('components.navbar')

    <!-- Main Content Injection -->
    <main>
        @if(session('success'))
            <div class="container" style="margin-top:1.5rem;">
                <div style="background:#d1fae5; border:1px solid #a7f3d0; color:#065f46; padding:0.85rem 1.25rem; border-radius:8px; display:flex; justify-content:space-between; align-items:center;">
                    <div>✅ {{ session('success') }}</div>
                    <button onclick="this.parentElement.remove()" style="background:none; border:none; cursor:pointer; font-size:1.1rem; color:#065f46;">&times;</button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container" style="margin-top:1.5rem;">
                <div style="background:#fee2e2; border:1px solid #fecdd3; color:#991b1b; padding:0.85rem 1.25rem; border-radius:8px; display:flex; justify-content:space-between; align-items:center;">
                    <div>⚠️ {{ session('error') }}</div>
                    <button onclick="this.parentElement.remove()" style="background:none; border:none; cursor:pointer; font-size:1.1rem; color:#991b1b;">&times;</button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Emergency SOS Modal -->
    @include('components.sos-modal')

    <!-- AI Yatri Assistant Chatbot -->
    @include('components.ai-chatbot')

    <!-- Production Footer -->
    @include('components.footer')

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Yatri Core Scripts -->
    <script src="{{ asset('js/yatri-app.js') }}"></script>
    <script src="{{ asset('js/yatri-map.js') }}"></script>
    <script src="{{ asset('js/yatri-ai.js') }}"></script>
    @stack('scripts')
</body>
</html>
