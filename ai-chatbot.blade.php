<!-- Floating AI Yatri Assistant Widget -->
<div id="aiChatBubble" class="ai-chat-bubble" title="Chat with AI Yatri Assistant">
    🤖
</div>

<div id="aiChatWindow" class="ai-chat-window">
    <div class="ai-chat-header">
        <div style="display:flex; align-items:center; gap:0.5rem;">
            <div style="font-size:1.25rem;">🕉️</div>
            <div>
                <strong style="font-size:0.95rem; display:block;">AI Yatri Assistant</strong>
                <span style="font-size:0.72rem; color:#cbd5e1;">Bilingual Pilgrim Guide</span>
            </div>
        </div>
        <div style="display:flex; align-items:center; gap:0.5rem;">
            <select id="aiLanguageSelect" style="background:#1e293b; color:#fff; border:1px solid #334155; border-radius:4px; font-size:0.75rem; padding:2px 4px;">
                <option value="en">English</option>
                <option value="hi">हिन्दी</option>
            </select>
            <button id="aiChatClose" type="button" style="background:none; border:none; color:#cbd5e1; font-size:1.2rem; cursor:pointer;">&times;</button>
        </div>
    </div>

    <!-- Suggested Quick Chips -->
    <div style="padding:0.5rem 0.75rem; background:#f1f5f9; display:flex; gap:0.4rem; overflow-x:auto; border-bottom:1px solid #e2e8f0;">
        <button type="button" onclick="askAiQuick('How to get RFID Yatra Parchi?')" style="white-space:nowrap; background:#fff; border:1px solid #cbd5e1; border-radius:12px; font-size:0.72rem; padding:2px 8px; cursor:pointer;">RFID Slip</button>
        <button type="button" onclick="askAiQuick('Battery Car rules for seniors')" style="white-space:nowrap; background:#fff; border:1px solid #cbd5e1; border-radius:12px; font-size:0.72rem; padding:2px 8px; cursor:pointer;">Senior Support</button>
        <button type="button" onclick="askAiQuick('Emergency medical post locations')" style="white-space:nowrap; background:#fff; border:1px solid #cbd5e1; border-radius:12px; font-size:0.72rem; padding:2px 8px; cursor:pointer;">Medical Aid</button>
    </div>

    <div id="aiChatMessages" class="ai-chat-messages">
        <div class="chat-msg bot">
            🙏 <strong>Jai Mata Di / Har Har Mahadev!</strong><br>
            Welcome to the <strong>Yatri Sahayata</strong> smart assistant. Ask me anything about RFID slips, battery cars, oxygen posts, or route shelters.
            <div style="font-size:0.68rem; color:#64748b; margin-top:0.35rem;">Guardrail: Verified shrine logistics only. In medical emergency, tap the SOS button.</div>
        </div>
    </div>

    <form id="aiChatForm" class="ai-chat-input-bar">
        <input type="text" id="aiChatInput" class="form-control" style="font-size:0.85rem; padding:0.5rem 0.75rem;" placeholder="Ask in Hindi or English..." required autocomplete="off">
        <button type="submit" class="btn btn-primary" style="padding:0.5rem 0.85rem; font-size:0.85rem;">Send</button>
    </form>
</div>
