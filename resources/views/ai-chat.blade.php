@extends('layouts.app')

@section('title', 'Monefy AI Assistant')

@push('styles')
<style>
    :root {
        --ai-primary: #6366F1;
        --ai-secondary: #8B5CF6;
        --ai-light: #EEF2FF;
        --ai-user-bubble: #F8FAFC;
    }

    body {
        background-color: #F8FAFC !important;
    }

    .chat-container {
        height: calc(100vh - 180px);
        background: white;
        border-radius: 24px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid #F1F5F9;
    }

    .chat-header {
        background: linear-gradient(135deg, var(--ai-primary), var(--ai-secondary));
        padding: 1.5rem;
        color: white;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .ai-avatar {
        width: 45px;
        height: 45px;
        background: rgba(255,255,255,0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        backdrop-filter: blur(5px);
    }

    .chat-body {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.2rem;
        background: #FAFAFA;
    }

    .chat-bubble {
        max-width: 80%;
        padding: 1rem 1.2rem;
        border-radius: 20px;
        line-height: 1.5;
        position: relative;
        font-size: 0.95rem;
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .chat-bubble.ai {
        align-self: flex-start;
        background: white;
        border: 1px solid #F1F5F9;
        color: #1E293B;
        border-bottom-left-radius: 4px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }

    .chat-bubble.user {
        align-self: flex-end;
        background: var(--ai-primary);
        color: white;
        border-bottom-right-radius: 4px;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.2);
    }

    .chat-footer {
        padding: 1.2rem;
        background: white;
        border-top: 1px solid #F1F5F9;
    }

    .chat-input-wrapper {
        display: flex;
        gap: 10px;
        background: #F8FAFC;
        padding: 8px;
        border-radius: 20px;
        border: 1px solid #E2E8F0;
    }

    .chat-input {
        flex: 1;
        border: none;
        background: transparent;
        padding: 0.5rem 1rem;
        outline: none;
        resize: none;
        height: 44px;
        font-size: 0.95rem;
    }

    .btn-send {
        background: linear-gradient(135deg, var(--ai-primary), var(--ai-secondary));
        color: white;
        border: none;
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
    }

    .btn-send:hover {
        transform: scale(1.05);
    }

    .btn-send:disabled {
        background: #CBD5E0;
        cursor: not-allowed;
        transform: none;
    }

    .typing-indicator {
        display: flex;
        gap: 4px;
        padding: 5px 0;
    }
    
    .typing-dot {
        width: 6px;
        height: 6px;
        background: #94A3B8;
        border-radius: 50%;
        animation: typing 1.4s infinite ease-in-out;
    }
    
    .typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .typing-dot:nth-child(2) { animation-delay: -0.16s; }
    
    @keyframes typing {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }

    /* Markdown styling inside AI bubble */
    .chat-bubble.ai strong { color: var(--ai-primary); }
    .chat-bubble.ai ul { padding-left: 1.2rem; margin-top: 0.5rem; margin-bottom: 0; }
    .chat-bubble.ai li { margin-bottom: 0.3rem; }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="chat-container">
                <div class="chat-header">
                    <div class="ai-avatar">
                        <i class="bi bi-robot"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">Monefy AI Assistant</h5>
                        <small class="opacity-75">Tanya seputar pengeluaran, tabungan, & tips</small>
                    </div>
                </div>

                <div class="chat-body" id="chatBody">
                    <div class="chat-bubble ai">
                        Halo! Saya adalah <strong>Monefy AI</strong> 👋<br>
                        Saya bisa melihat ringkasan keuanganmu secara aman. Apa yang ingin kamu tanyakan hari ini?<br><br>
                        Contoh:<br>
                        - "Bulan ini aku paling boros beli apa?"<br>
                        - "Apakah uangku cukup untuk beli barang di Wishlist?"<br>
                        - "Beri aku tips menabung bulan ini!"
                    </div>
                </div>

                <div class="chat-footer">
                    <form id="chatForm" class="chat-input-wrapper">
                        <input type="text" id="chatInput" class="chat-input" placeholder="Ketik pertanyaanmu di sini..." required autocomplete="off">
                        <button type="submit" id="chatSubmit" class="btn-send">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const chatBody = document.getElementById('chatBody');
    const chatForm = document.getElementById('chatForm');
    const chatInput = document.getElementById('chatInput');
    const chatSubmit = document.getElementById('chatSubmit');

    function appendMessage(sender, text) {
        const bubble = document.createElement('div');
        bubble.className = `chat-bubble ${sender}`;
        
        // Simple markdown parsing for bold and list
        if(sender === 'ai') {
            text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            text = text.replace(/\*(.*?)\*/g, '<em>$1</em>');
            text = text.replace(/\n- (.*?)/g, '<br>• $1');
            text = text.replace(/\n/g, '<br>');
        }
        
        bubble.innerHTML = text;
        chatBody.appendChild(bubble);
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    function showTyping() {
        const bubble = document.createElement('div');
        bubble.className = `chat-bubble ai typing-bubble`;
        bubble.id = 'typingIndicator';
        bubble.innerHTML = `
            <div class="typing-indicator">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
        `;
        chatBody.appendChild(bubble);
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    function removeTyping() {
        const indicator = document.getElementById('typingIndicator');
        if (indicator) indicator.remove();
    }

    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const message = chatInput.value.trim();
        if (!message) return;

        // Tampilkan pesan user
        appendMessage('user', message);
        chatInput.value = '';
        chatSubmit.disabled = true;
        chatInput.disabled = true;

        // Tampilkan "AI is typing..."
        showTyping();

        try {
            const response = await fetch('{{ route("ai.chat") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();
            
            removeTyping();
            appendMessage('ai', data.reply);
        } catch (error) {
            removeTyping();
            appendMessage('ai', 'Maaf, terjadi masalah koneksi jaringan.');
        } finally {
            chatSubmit.disabled = false;
            chatInput.disabled = false;
            chatInput.focus();
        }
    });
</script>
@endpush
@endsection
