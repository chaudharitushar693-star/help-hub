/**
 * YATRI SAHAYATA – Core Client Application Logic
 * Developer: Nidhi Chaudhary (0108CS241082)
 * Samrat Ashok Technological Institute, Vidisha, MP
 */

document.addEventListener('DOMContentLoaded', () => {
    initSmartSearch();
    initEmergencySos();
    initLanguageSwitcher();
    initFaqAccordion();
});

// Toast System
function showToast(message, type = 'info') {
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <strong>${type === 'error' ? '⚠️ Notice' : type === 'success' ? '✅ Success' : 'ℹ️ Information'}</strong>
            <button onclick="this.parentElement.parentElement.remove()" style="background:none; border:none; cursor:pointer; font-size:1rem;">&times;</button>
        </div>
        <div style="margin-top:0.35rem; font-size:0.875rem;">${message}</div>
    `;

    container.appendChild(toast);
    setTimeout(() => {
        toast.style.transition = 'opacity 0.4s';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 400);
    }, 4500);
}

// Smart Search with Live Autocomplete
function initSmartSearch() {
    const searchInput = document.getElementById('mainSearchInput');
    const resultsBox = document.getElementById('searchAutocomplete');
    if (!searchInput || !resultsBox) return;

    let debounceTimer;

    searchInput.addEventListener('input', (e) => {
        clearTimeout(debounceTimer);
        const q = e.target.value.trim();

        if (q.length < 2) {
            resultsBox.style.display = 'none';
            resultsBox.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`/api/autocomplete?q=${encodeURIComponent(q)}`)
                .then(res => res.json())
                .then(data => {
                    if (!data.suggestions || data.suggestions.length === 0) {
                        resultsBox.innerHTML = `
                            <div style="padding:1rem; text-align:center; color:#64748b; font-size:0.85rem;">
                                No verified facilities found for "<strong>${escapeHtml(q)}</strong>".<br>
                                Try searching for <em>Katra, Battery Car, Clinic, Cloak Room</em>.
                            </div>
                        `;
                        resultsBox.style.display = 'block';
                        return;
                    }

                    resultsBox.innerHTML = data.suggestions.map(s => `
                        <a href="${s.url}" class="autocomplete-item">
                            <div>
                                <strong style="font-size:0.92rem;">${escapeHtml(s.title)}</strong>
                                <div style="font-size:0.75rem; color:#64748b;">${escapeHtml(s.circuit)}</div>
                            </div>
                            <span class="autocomplete-badge">${escapeHtml(s.type)}</span>
                        </a>
                    `).join('');
                    resultsBox.style.display = 'block';
                })
                .catch(() => {
                    resultsBox.style.display = 'none';
                });
        }, 250);
    });

    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
            resultsBox.style.display = 'none';
        }
    });
}

// Emergency SOS Modal & Location Sharing
function initEmergencySos() {
    const sosModal = document.getElementById('sosModal');
    const openBtn = document.getElementById('openSosBtn');
    const closeBtn = document.getElementById('closeSosBtn');
    const shareLocBtn = document.getElementById('shareLocationBtn');
    const locStatus = document.getElementById('locationStatus');
    const latInput = document.getElementById('sosLatitude');
    const lngInput = document.getElementById('sosLongitude');
    const locTextInput = document.getElementById('sosCurrentLocation');

    if (!sosModal) return;

    if (openBtn) {
        openBtn.addEventListener('click', (e) => {
            e.preventDefault();
            sosModal.style.display = 'flex';
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            sosModal.style.display = 'none';
        });
    }

    if (shareLocBtn && navigator.geolocation) {
        shareLocBtn.addEventListener('click', () => {
            locStatus.innerHTML = '🛰️ Requesting device GPS coordinates with your consent...';
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    latInput.value = pos.coords.latitude.toFixed(6);
                    lngInput.value = pos.coords.longitude.toFixed(6);
                    locStatus.innerHTML = `✅ GPS Captured: <strong>${pos.coords.latitude.toFixed(4)}°, ${pos.coords.longitude.toFixed(4)}°</strong> (Accuracy: ±${Math.round(pos.coords.accuracy)}m)`;
                    if (!locTextInput.value.trim()) {
                        locTextInput.value = `Near GPS Lat ${pos.coords.latitude.toFixed(4)}, Lon ${pos.coords.longitude.toFixed(4)}`;
                    }
                    shareLocBtn.style.background = '#10b981';
                    shareLocBtn.innerText = 'GPS Coordinates Shared';
                },
                (err) => {
                    locStatus.innerHTML = `⚠️ Location permission was not granted (${err.message}). Please type your track milestone or landmark manually below.`;
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        });
    }

    // Handle SOS Form submission via AJAX
    const sosForm = document.getElementById('sosRequestForm');
    if (sosForm) {
        sosForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const submitBtn = sosForm.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerText = 'Dispatching Emergency Alert...';

            const formData = new FormData(sosForm);
            fetch('/assistance/submit', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    sosModal.style.display = 'none';
                    showToast(`🚨 Alert Logged! Tracking Code: ${data.tracking_code}`, 'error');
                    window.location.href = data.track_url;
                } else {
                    showToast(data.message || 'Error submitting request', 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Submit Emergency Assistance Request';
                }
            })
            .catch(() => {
                // Fallback to standard form submission
                sosForm.submit();
            });
        });
    }
}

// Language Switcher Toggle
function initLanguageSwitcher() {
    const langSelect = document.getElementById('globalLanguageSelect');
    if (!langSelect) return;

    langSelect.addEventListener('change', (e) => {
        const lang = e.target.value;
        localStorage.setItem('yatri_lang', lang);
        document.documentElement.lang = lang;
        showToast(lang === 'hi' ? 'भाषा बदलकर हिंदी कर दी गई है।' : 'Language changed to English.', 'info');
        
        // Notify AI widget if open
        const aiLang = document.getElementById('aiLanguageSelect');
        if (aiLang) aiLang.value = lang;
    });

    const savedLang = localStorage.getItem('yatri_lang');
    if (savedLang) {
        langSelect.value = savedLang;
        document.documentElement.lang = savedLang;
    }
}

// FAQ Accordion
function initFaqAccordion() {
    document.querySelectorAll('.faq-question').forEach(btn => {
        btn.addEventListener('click', () => {
            const answer = btn.nextElementSibling;
            const isOpen = answer.style.display === 'block';
            document.querySelectorAll('.faq-answer').forEach(a => a.style.display = 'none');
            document.querySelectorAll('.faq-question span.toggle-icon').forEach(icon => icon.innerText = '+');

            if (!isOpen) {
                answer.style.display = 'block';
                btn.querySelector('span.toggle-icon').innerText = '−';
            }
        });
    });
}

// Utility: Escape HTML
function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>"']/g, (m) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    }[m]));
}
