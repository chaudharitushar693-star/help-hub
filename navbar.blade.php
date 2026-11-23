<header class="navbar">
    <div class="container nav-inner">
        <a href="{{ route('home') }}" class="brand-wrap">
            <div class="brand-logo-icon">🕉️</div>
            <div class="brand-text">
                <h1>YATRI SAHAYATA</h1>
                <span>Your Journey, Our Responsibility</span>
            </div>
        </a>

        <ul class="nav-links">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('routes') }}" class="{{ request()->routeIs('routes') ? 'active' : '' }}">Routes & Map</a></li>
            <li><a href="{{ route('planner.index') }}" class="{{ request()->routeIs('planner.*') ? 'active' : '' }}">Smart Planner</a></li>
            <li><a href="{{ route('medical') }}" class="{{ request()->routeIs('medical') ? 'active' : '' }}">Medical</a></li>
            <li><a href="{{ route('accommodation') }}" class="{{ request()->routeIs('accommodation') ? 'active' : '' }}">Stay</a></li>
            <li><a href="{{ route('announcements') }}" class="{{ request()->routeIs('announcements') ? 'active' : '' }}">Advisories</a></li>
            <li><a href="{{ route('lost-found') }}" class="{{ request()->routeIs('lost-found') ? 'active' : '' }}">Lost & Found</a></li>
            <li><a href="{{ route('faqs') }}" class="{{ request()->routeIs('faqs') ? 'active' : '' }}">FAQs</a></li>
        </ul>

        <div class="nav-actions">
            <!-- Language Selector -->
            <select id="globalLanguageSelect" class="lang-select" title="Choose Language">
                <option value="en">English (EN)</option>
                <option value="hi">हिन्दी (HI)</option>
            </select>

            <!-- Emergency SOS Button -->
            <button id="openSosBtn" type="button" class="btn-sos" title="Trigger Emergency Assistance">
                <span>🚨</span> SOS EMERGENCY
            </button>

            <!-- User Auth Links -->
            @auth
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-gold">Admin Portal</a>
                @else
                    <a href="{{ route('user.dashboard') }}" class="btn btn-sm btn-outline-gold">My Dashboard</a>
                @endif
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-secondary" style="padding:0.4rem 0.65rem;">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-sm btn-secondary">Sign In</a>
                <a href="{{ route('register') }}" class="btn btn-sm btn-primary">Register</a>
            @endauth
        </div>
    </div>
</header>
