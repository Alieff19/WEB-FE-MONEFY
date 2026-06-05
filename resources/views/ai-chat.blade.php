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

    /* Voice Call Overlay Styles */
    .voice-call-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: radial-gradient(circle at center, #1E1B4B 0%, #0F0E17 100%);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.4s ease;
    }

    .call-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        max-width: 480px;
        height: 100%;
        max-height: 640px;
        padding: 3rem 2rem;
        text-align: center;
    }

    .ai-profile-container {
        position: relative;
        margin-top: 2rem;
    }

    .ai-avatar-large {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, var(--ai-primary), var(--ai-secondary));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 4px solid rgba(255, 255, 255, 0.1);
        z-index: 2;
        position: relative;
    }

    .pulse-ring {
        position: absolute;
        top: -10px;
        left: -10px;
        width: 140px;
        height: 140px;
        border: 2px solid var(--ai-primary);
        border-radius: 50%;
        animation: pulse 2s infinite ease-out;
        z-index: 1;
        opacity: 0;
    }

    @keyframes pulse {
        0% { transform: scale(0.9); opacity: 0; }
        50% { opacity: 0.5; }
        100% { transform: scale(1.3); opacity: 0; }
    }

    .call-status {
        color: #A5B4FC;
        font-size: 1.1rem;
        margin-top: 1rem;
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    /* Voice Waveform */
    .voice-wave-container {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 6px;
        height: 60px;
        margin-top: 2rem;
    }

    .voice-wave-container .bar {
        width: 6px;
        height: 8px;
        background-color: var(--ai-primary);
        border-radius: 3px;
        transition: height 0.15s ease;
        animation: wave-idle 1.2s infinite ease-in-out;
    }

    .voice-wave-container .bar:nth-child(1) { animation-delay: 0.1s; }
    .voice-wave-container .bar:nth-child(2) { animation-delay: 0.2s; }
    .voice-wave-container .bar:nth-child(3) { animation-delay: 0.3s; }
    .voice-wave-container .bar:nth-child(4) { animation-delay: 0.4s; }
    .voice-wave-container .bar:nth-child(5) { animation-delay: 0.3s; }
    .voice-wave-container .bar:nth-child(6) { animation-delay: 0.2s; }
    .voice-wave-container .bar:nth-child(7) { animation-delay: 0.1s; }

    @keyframes wave-idle {
        0%, 100% { height: 8px; }
        50% { height: 16px; }
    }

    /* Active voice wave when speaking */
    .voice-wave-container.speaking .bar {
        background-color: #10B981 !important; /* Green for speaking */
        animation: wave-speak 1s infinite ease-in-out;
    }
    .voice-wave-container.speaking .bar:nth-child(1) { animation-duration: 0.8s; }
    .voice-wave-container.speaking .bar:nth-child(2) { animation-duration: 0.9s; }
    .voice-wave-container.speaking .bar:nth-child(3) { animation-duration: 0.7s; }
    .voice-wave-container.speaking .bar:nth-child(4) { animation-duration: 1.1s; }
    .voice-wave-container.speaking .bar:nth-child(5) { animation-duration: 0.6s; }
    .voice-wave-container.speaking .bar:nth-child(6) { animation-duration: 1.0s; }
    .voice-wave-container.speaking .bar:nth-child(7) { animation-duration: 0.8s; }

    @keyframes wave-speak {
        0%, 100% { height: 12px; }
        50% { height: 48px; }
    }

    /* Subtitles Container */
    .subtitles-container {
        display: none !important; /* Hide subtitles container for a clean, pure voice call experience */
        width: 100%;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 16px;
        padding: 1.5rem;
        min-height: 100px;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        margin-top: 1.5rem;
    }

    /* Controls */
    .call-controls {
        display: flex;
        gap: 20px;
        margin-top: auto;
        margin-bottom: 2rem;
    }

    .control-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        border: none;
        background: rgba(255, 255, 255, 0.1);
        color: white;
        font-size: 1.4rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
        cursor: pointer;
    }

    .control-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.05);
    }

    .control-btn.muted {
        background: #EF4444 !important;
    }

    .control-btn.end-call {
        background: #EF4444;
    }

    .control-btn.end-call:hover {
        background: #DC2626;
    }
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
                    <button id="startCallBtn" class="btn btn-outline-light rounded-pill px-3 d-flex align-items-center gap-2 btn-sm ms-auto" style="border: 1px solid rgba(255,255,255,0.4); background: rgba(255,255,255,0.1); transition: 0.3s;">
                        <i class="bi bi-telephone-fill"></i> Panggilan Suara
                    </button>
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

<!-- Voice Call Overlay -->
<div id="voiceCallOverlay" class="voice-call-overlay d-none">
    <div class="call-card animate-fadeIn">
        <div class="ai-profile-container">
            <div class="ai-avatar-large shadow-lg">
                <i class="bi bi-robot text-white fs-1"></i>
            </div>
            <div class="pulse-ring"></div>
        </div>
        
        <h3 class="fw-bold mt-4 text-white">Monefy AI</h3>
        <p id="callStatus" class="call-status">Menghubungkan...</p>
        
        <!-- Voice Waveform Animation -->
        <div class="voice-wave-container mb-5">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>

        <!-- Live Subtitles / Text Log of the call -->
        <div class="subtitles-container">
            <p id="subtitlesText" class="text-white-50 small text-center px-4 italic">"Mencoba tersambung..."</p>
        </div>

        <!-- Call Controls -->
        <div class="call-controls">
            <button id="toggleMuteBtn" class="control-btn" title="Mute/Unmute">
                <i class="bi bi-mic-fill"></i>
            </button>
            <button id="endCallBtn" class="control-btn end-call" title="End Call">
                <i class="bi bi-telephone-x-fill"></i>
            </button>
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

    // Voice Call Logic
    const startCallBtn = document.getElementById('startCallBtn');
    const voiceCallOverlay = document.getElementById('voiceCallOverlay');
    const callStatus = document.getElementById('callStatus');
    const subtitlesText = document.getElementById('subtitlesText');
    const toggleMuteBtn = document.getElementById('toggleMuteBtn');
    const endCallBtn = document.getElementById('endCallBtn');
    const waveContainer = document.querySelector('.voice-wave-container');

    let recognition = null;
    let isCallActive = false;
    let isMuted = false;
    let isAiSpeaking = false; // State to strictly block microphone while AI speaks or thinks
    let synth = window.speechSynthesis;
    let speakUtterance = null;

    // Check support for speech recognition
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

    startCallBtn.addEventListener('click', function() {
        if (!SpeechRecognition) {
            alert("Maaf, browser Anda tidak mendukung Speech Recognition (Perekam Suara). Gunakan Google Chrome atau Microsoft Edge terbaru.");
            return;
        }
        
        // Open overlay
        voiceCallOverlay.classList.remove('d-none');
        isCallActive = true;
        isAiSpeaking = false;
        callStatus.textContent = "Menghubungkan...";
        subtitlesText.textContent = "Mencoba tersambung dengan Monefy AI...";
        
        // Start Call flow
        startCallSession();
    });

    function startCallSession() {
        // Init Speech Recognition
        recognition = new SpeechRecognition();
        recognition.lang = 'id-ID'; // Indonesian
        recognition.interimResults = false;
        recognition.maxAlternatives = 1;

        recognition.onstart = () => {
            if (isCallActive && !isAiSpeaking && !isMuted) {
                callStatus.textContent = "Mendengarkan...";
                waveContainer.classList.remove('speaking');
            }
        };

        recognition.onerror = (event) => {
            console.error("Speech Recognition Error:", event.error);
            if (event.error === 'no-speech' && isCallActive && !isAiSpeaking && !isMuted) {
                // If silence, restart listening loop safely
                restartListening();
            }
        };

        recognition.onend = () => {
            // Restart ONLY if AI is not speaking/thinking
            if (isCallActive && !isAiSpeaking && !isMuted) {
                restartListening();
            }
        };

        recognition.onresult = async (event) => {
            const transcript = event.results[0][0].transcript;
            subtitlesText.innerHTML = `<strong>Anda:</strong> "${transcript}"`;
            
            // Mark AI as thinking/speaking and stop mic immediately
            isAiSpeaking = true;
            recognition.stop();
            callStatus.textContent = "Sedang Berpikir...";
            
            // Send to backend Gemini
            try {
                const response = await fetch('{{ route("ai.chat") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: transcript })
                });
                
                const data = await response.json();
                
                if (isCallActive) {
                    speakAIResponse(data.reply);
                }
            } catch (error) {
                console.error("Fetch error:", error);
                if (isCallActive) {
                    speakAIResponse("Maaf, koneksi internet sedang terganggu. Tolong ulangi lagi.");
                }
            }
        };

        // Connect animation delay (simulate VoIP connection)
        setTimeout(() => {
            if (isCallActive) {
                callStatus.textContent = "Tersambung";
                speakAIResponse("Halo! Saya asisten suara Monefy AI. Ada yang bisa saya bantu terkait keuanganmu hari ini?");
            }
        }, 1500);
    }

    // Force load voices for Chrome/Edge
    if (synth && synth.onvoiceschanged !== undefined) {
        synth.onvoiceschanged = () => {
            getIndonesianVoice();
        };
    }

    function getIndonesianVoice() {
        const voices = synth.getVoices();
        let idVoices = voices.filter(v => v.lang === 'id-ID' || v.lang.startsWith('id') || v.lang.toLowerCase().includes('indonesia'));
        
        if (idVoices.length === 0) {
            idVoices = voices.filter(v => v.name.toLowerCase().includes('indonesia') || v.name.toLowerCase().includes('gadis') || v.name.toLowerCase().includes('ardi'));
        }

        if (idVoices.length === 0) return null;
        
        // Priority: Natural Microsoft Voices -> Standard Microsoft -> Google Indonesian -> Fallback
        const naturalGadis = idVoices.find(v => v.name.includes('Gadis') && v.name.includes('Natural'));
        if (naturalGadis) return naturalGadis;
        
        const naturalArdi = idVoices.find(v => v.name.includes('Ardi') && v.name.includes('Natural'));
        if (naturalArdi) return naturalArdi;
        
        const gadis = idVoices.find(v => v.name.includes('Gadis'));
        if (gadis) return gadis;

        const ardi = idVoices.find(v => v.name.includes('Ardi'));
        if (ardi) return ardi;

        const googleId = idVoices.find(v => v.name.includes('Google'));
        if (googleId) return googleId;

        return idVoices[0];
    }

    function speakAIResponse(text) {
        // Enforce AI speaking state
        isAiSpeaking = true;
        synth.cancel();
        
        // Clean text from markdown bold/bullets before reading
        const cleanedText = text
            .replace(/\*\*(.*?)\*\*/g, '$1')
            .replace(/\*(.*?)\*/g, '$1')
            .replace(/- /g, '')
            .replace(/<br>/g, ' ');

        subtitlesText.innerHTML = `<strong>Monefy AI:</strong> "${text.replace(/\n/g, '<br>')}"`;
        
        speakUtterance = new SpeechSynthesisUtterance(cleanedText);
        
        // Explicitly set language first to prevent foreign accent fallback
        speakUtterance.lang = 'id-ID';
        
        // Apply high-quality Indonesian voice if available
        const indoVoice = getIndonesianVoice();
        if (indoVoice) {
            speakUtterance.voice = indoVoice;
        }
        
        // Set speech speed slightly faster for natural flow
        speakUtterance.rate = 1.05; 

        speakUtterance.onstart = () => {
            callStatus.textContent = "Berbicara...";
            waveContainer.classList.add('speaking');
        };

        speakUtterance.onend = () => {
            if (isCallActive) {
                isAiSpeaking = false; // Release lock
                callStatus.textContent = "Mendengarkan...";
                waveContainer.classList.remove('speaking');
                restartListening();
            }
        };

        speakUtterance.onerror = (e) => {
            console.error("Speech Synthesis Error:", e);
            if (isCallActive) {
                isAiSpeaking = false; // Release lock on error
                restartListening();
            }
        };

        synth.speak(speakUtterance);
    }

    function restartListening() {
        if (!isCallActive || isMuted || isAiSpeaking) return;
        try {
            recognition.start();
        } catch (e) {
            // Already active or starting
        }
    }

    toggleMuteBtn.addEventListener('click', function() {
        isMuted = !isMuted;
        if (isMuted) {
            toggleMuteBtn.classList.add('muted');
            toggleMuteBtn.innerHTML = '<i class="bi bi-mic-mute-fill"></i>';
            callStatus.textContent = "Mikrofon Senyap";
            if (recognition) recognition.stop();
        } else {
            toggleMuteBtn.classList.remove('muted');
            toggleMuteBtn.innerHTML = '<i class="bi bi-mic-fill"></i>';
            callStatus.textContent = "Mendengarkan...";
            restartListening();
        }
    });

    endCallBtn.addEventListener('click', endCallSession);

    function endCallSession() {
        isCallActive = false;
        isAiSpeaking = false;
        if (recognition) {
            recognition.stop();
        }
        if (synth) {
            synth.cancel();
        }
        voiceCallOverlay.classList.add('d-none');
        waveContainer.classList.remove('speaking');
    }
</script>
@endpush
@endsection
