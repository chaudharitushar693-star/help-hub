/**
 * YATRI SAHAYATA – AI Yatri Assistant Client Widget
 * Dual Language (Hindi & English), Safe Guardrails, Ground Truth Citation
 * Developer: Nidhi Chaudhary (0108CS241082), SATI Vidisha
 */

document.addEventListener('DOMContentLoaded', () => {
    initAiChatWidget();
});

function initAiChatWidget() {
    const bubble = document.getElementById('aiChatBubble');
    const windowEl = document.getElementById('aiChatWindow');
    const closeBtn = document.getElementById('aiChatClose');
    const form = document.getElementById('aiChatForm');
    const input = document.getElementById('aiChatInput');
    const msgList = document.getElementById('aiChatMessages');
    const langSelect = document.getElementById('aiLanguageSelect');

    if (!bubble || !windowEl) return;

    bubble.addEventListener('click', () => {
        const isHidden = windowEl.style.display === 'none' || !windowEl.style.display;
        windowEl.style.display = isHidden ? 'flex' : 'none';
        if (isHidden && input) input.focus();
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            windowEl.style.display = 'none';
        });
    }

    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const message = input.value.trim();
            if (!message) return;

            appendMessage('user', message);
            input.value = '';

            // Show typing indicator
            const typingId = appendTypingIndicator();

            const language = langSelect ? langSelect.value : 'en';

            fetch('/api/ai/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ message, language })
            })
            .then(res => res.json())
            .then(data => {
                removeTypingIndicator(typingId);
                if (data.success) {
                    appendMessage('bot', data.reply, data.source);
                    if (data.action === 'open_sos_modal') {
                        const sosBtn = document.getElementById('openSosBtn');
                        if (sosBtn) sosBtn.click();
                    }
                } else {
                    appendMessage('bot', 'I apologize, but I could not reach the knowledge service. Please dial 112 for urgent emergency support.');
                }
            })
            .catch(() => {
                removeTypingIndicator(typingId);
                appendMessage('bot', 'Network connectivity is limited. For urgent medical support, dial 112 or contact the nearest Shrine First Aid Post.');
            });
        });
    }

    function appendMessage(sender, text, source = null) {
        const msgDiv = document.createElement('div');
        msgDiv.className = `chat-msg ${sender}`;

        let formattedText = escapeHtml(text).replace(/\n/g, '<br>');
        formattedText = formattedText.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        formattedText = formattedText.replace(/`([^`]+)`/g, '<code style="background:#e2e8f0; padding:2px 4px; border-radius:3px;">$1</code>');

        let html = formattedText;
        if (source) {
            html += `<div style="font-size:0.68rem; color:#64748b; margin-top:0.4rem; border-top:1px dashed #cbd5e1; padding-top:0.25rem;">Source: ${escapeHtml(source)}</div>`;
        }

        msgDiv.innerHTML = html;
        msgList.appendChild(msgDiv);
        msgList.scrollTop = msgList.scrollHeight;
    }

    function appendTypingIndicator() {
        const id = 'typing_' + Date.now();
        const div = document.createElement('div');
        div.id = id;
        div.className = 'chat-msg bot';
        div.style.color = '#94a3b8';
        div.style.fontStyle = 'italic';
        div.innerHTML = 'Thinking with verified pilgrim guide...';
        msgList.appendChild(div);
        msgList.scrollTop = msgList.scrollHeight;
        return id;
    }

    function removeTypingIndicator(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }
}

// Quick chip trigger
function askAiQuick(question) {
    const bubble = document.getElementById('aiChatBubble');
    const windowEl = document.getElementById('aiChatWindow');
    const input = document.getElementById('aiChatInput');
    const form = document.getElementById('aiChatForm');

    if (windowEl && windowEl.style.display !== 'flex') {
        windowEl.style.display = 'flex';
    }
    if (input) {
        input.value = question;
        if (form) form.dispatchEvent(new Event('submit'));
    }
}
