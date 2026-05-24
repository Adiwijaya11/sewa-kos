@extends(auth()->user()->role === 'user' ? 'layouts.app' : 'layouts.dashboard')

@section('title', 'Pesan Obrolan - KosinAja')
@section('header_title', 'Obrolan Chat')

@section('content')
<div class="{{ auth()->user()->role === 'user' ? 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8' : '' }}">
    <!-- Card Container -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col md:flex-row h-[calc(100vh-12rem)] min-h-[550px] max-h-[750px]">
        
        <!-- Left Sidebar: Conversation List -->
        <div class="w-full md:w-80 border-r border-slate-100 flex flex-col {{ isset($chatPartner) ? 'hidden md:flex' : 'flex' }}">
            <!-- Header Search/Title -->
            <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-bold text-slate-800 text-base">Kotak Masuk</h3>
                <p class="text-xs text-slate-400">Hubungi penyewa atau pemilik kos secara langsung</p>
            </div>
            
            <!-- List of Conversations -->
            <div class="flex-grow overflow-y-auto divide-y divide-slate-50">
                @forelse($conversations as $convo)
                    @php
                        $user = $convo['user'];
                        $lastMsg = $convo['last_message'];
                        $unread = $convo['unread_count'];
                        $isActive = isset($chatPartner) && $chatPartner->id === $user->id;
                    @endphp
                    <a href="{{ route('chats.conversation', $user->id) }}" 
                       class="flex items-center space-x-3.5 px-4 py-4 transition-all hover:bg-slate-50/80 {{ $isActive ? 'bg-emerald-50/50 border-l-4 border-emerald-500' : '' }}">
                        
                        <!-- Avatar -->
                        <div class="relative flex-shrink-0">
                            <div class="w-11 h-11 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-700 text-sm overflow-hidden shadow-sm">
                                @if($user->avatar)
                                    <img src="{{ asset($user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                @else
                                    {{ Str::upper(Str::substr($user->name, 0, 2)) }}
                                @endif
                            </div>
                            <!-- Role Indicator Dot -->
                            <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white flex items-center justify-center text-[8px] font-bold text-white shadow-sm {{ $user->role === 'owner' ? 'bg-emerald-500' : ($user->role === 'admin' ? 'bg-indigo-500' : 'bg-sky-500') }}" title="{{ $user->role }}">
                                {{ Str::upper(Str::substr($user->role, 0, 1)) }}
                            </span>
                        </div>
                        
                        <!-- Details -->
                        <div class="flex-grow min-w-0">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-semibold text-slate-800 truncate flex items-center">
                                    {{ $user->name }}
                                    @if($user->is_verified)
                                        <i class="fa-solid fa-circle-check text-emerald-500 ml-1 text-[10px]" title="Verified Owner"></i>
                                    @endif
                                </h4>
                                @if($lastMsg)
                                    <span class="text-[10px] text-slate-400 font-medium ml-1 flex-shrink-0">
                                        {{ $lastMsg->created_at->diffForHumans(null, true, true) }}
                                    </span>
                                @endif
                            </div>
                            
                            <p class="text-xs text-slate-500 truncate mt-0.5">
                                @if($lastMsg)
                                    @if($lastMsg->sender_id === auth()->id())
                                        <span class="text-slate-400">Anda:</span>
                                    @endif
                                    {{ $lastMsg->message ?? '[Kirim Gambar]' }}
                                @else
                                    <span class="italic text-slate-400">Mulai obrolan baru</span>
                                @endif
                            </p>
                        </div>
                        
                        <!-- Badge -->
                        @if($unread > 0)
                            <div class="flex-shrink-0 w-5 h-5 bg-emerald-500 text-slate-900 text-[10px] font-bold rounded-full flex items-center justify-center shadow-sm">
                                {{ $unread }}
                            </div>
                        @endif
                    </a>
                @empty
                    <div class="p-8 text-center text-slate-400 mt-10">
                        <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 mx-auto mb-3">
                            <i class="fa-regular fa-comment-dots text-xl"></i>
                        </div>
                        <p class="text-sm font-medium">Belum ada obrolan</p>
                        <p class="text-xs text-slate-400 mt-1">Gunakan fitur tanya pemilik di detail halaman kos.</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Right Sidebar: Active Chat Window -->
        <div class="flex-grow flex flex-col h-full bg-slate-50/30 {{ isset($chatPartner) ? 'flex' : 'hidden md:flex' }}">
            @if(isset($chatPartner))
                <!-- Chat Window Header -->
                <div class="px-5 py-3.5 border-b border-slate-100 bg-white flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-3.5">
                        <!-- Mobile back button -->
                        <a href="{{ route('chats.index') }}" class="md:hidden text-slate-500 hover:text-emerald-600 mr-1.5 transition-colors">
                            <i class="fa-solid fa-arrow-left text-lg"></i>
                        </a>
                        
                        <!-- Partner Avatar -->
                        <div class="relative flex-shrink-0">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-700 text-sm overflow-hidden">
                                @if($chatPartner->avatar)
                                    <img src="{{ asset($chatPartner->avatar) }}" alt="{{ $chatPartner->name }}" class="w-full h-full object-cover">
                                @else
                                    {{ Str::upper(Str::substr($chatPartner->name, 0, 2)) }}
                                @endif
                            </div>
                            <span class="absolute -bottom-1 -right-1 w-3.5 h-3.5 rounded-full border border-white flex items-center justify-center text-[7px] font-bold text-white shadow-sm {{ $chatPartner->role === 'owner' ? 'bg-emerald-500' : ($chatPartner->role === 'admin' ? 'bg-indigo-500' : 'bg-sky-500') }}">
                                {{ Str::upper(Str::substr($chatPartner->role, 0, 1)) }}
                            </span>
                        </div>
                        
                        <div>
                            <div class="flex items-center space-x-1">
                                <h3 class="font-bold text-slate-800 text-sm leading-none">{{ $chatPartner->name }}</h3>
                                @if($chatPartner->is_verified)
                                    <i class="fa-solid fa-circle-check text-emerald-500 text-xs" title="Verified Owner"></i>
                                @endif
                            </div>
                            <span class="text-[10px] text-slate-400 capitalize font-medium">{{ $chatPartner->role }} KosinAja</span>
                        </div>
                    </div>
                    
                    <!-- Quick actions -->
                    <div class="flex items-center space-x-2">
                        @if($chatPartner->role === 'owner')
                            <a href="{{ route('search') }}?owner_id={{ $chatPartner->id }}" 
                               class="px-3 py-1.5 rounded-lg border border-slate-100 hover:bg-slate-50 text-slate-600 text-xs font-semibold flex items-center transition-colors">
                                <i class="fa-solid fa-house-user mr-1.5 text-emerald-500"></i>Lihat Kos Owner
                            </a>
                        @endif
                    </div>
                </div>
                
                <!-- Messages History Area -->
                <div id="chatHistory" class="flex-grow p-5 overflow-y-auto bg-slate-50/50">
                    <div class="space-y-4">
                        @forelse($messages as $chat)
                            @include('chat.partials.message_bubble', ['chat' => $chat])
                        @empty
                            <div class="py-16 text-center text-slate-400">
                                <div class="w-14 h-14 rounded-full bg-white shadow-sm flex items-center justify-center text-emerald-500 mx-auto mb-4 border border-slate-100">
                                    <i class="fa-regular fa-comment-dots text-2xl"></i>
                                </div>
                                <p class="text-sm font-semibold text-slate-700">Mulai Obrolan Aman Anda</p>
                                <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto leading-relaxed">
                                    Tanya detail kos, negosiasi harga, atau sepakati survey lokasi. 
                                    <span class="text-emerald-600 font-semibold">Gunakan Pembayaran Escrow KosinAja</span> demi transaksi 100% aman anti scam.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
                
                <!-- Chat Send Panel -->
                <div class="p-4 bg-white border-t border-slate-100" x-data="chatSender()">
                    <!-- Image preview bar -->
                    <div x-show="imagePreview" class="mb-3 p-2 bg-slate-50 rounded-xl flex items-center justify-between border border-slate-100" x-cloak>
                        <div class="flex items-center space-x-3">
                            <img :src="imagePreview" class="w-12 h-12 object-cover rounded-lg border border-slate-200 shadow-sm" alt="Upload preview">
                            <div>
                                <span class="text-xs font-semibold text-slate-700 block">Siap Kirim Gambar</span>
                                <span class="text-[10px] text-slate-400" x-text="fileName"></span>
                            </div>
                        </div>
                        <button @click="clearImage()" class="w-7 h-7 rounded-full bg-slate-200/80 hover:bg-red-50 hover:text-red-500 flex items-center justify-center text-slate-500 transition-colors">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>
                    
                    <!-- Send Form -->
                    <form id="chatForm" action="{{ route('chats.send') }}" method="POST" enctype="multipart/form-data" @submit.prevent="sendMessage()">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $chatPartner->id }}">
                        
                        <div class="flex items-center space-x-2">
                            <!-- Image Attachment Button -->
                            <label class="flex-shrink-0 w-11 h-11 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 cursor-pointer transition-colors shadow-sm">
                                <i class="fa-regular fa-image text-lg"></i>
                                <input type="file" name="image" class="hidden" accept="image/*" @change="previewFile">
                            </label>
                            
                            <!-- Input Field -->
                            <div class="flex-grow relative">
                                <input type="text" 
                                       name="message" 
                                       x-model="msgText"
                                       placeholder="Tulis pesan Anda disini..." 
                                       class="w-full h-11 px-4 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-sm bg-slate-50/50"
                                       :required="!imageSelected">
                            </div>
                            
                            <!-- Send Button -->
                            <button type="submit" 
                                    class="flex-shrink-0 w-11 h-11 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white flex items-center justify-center shadow-md shadow-emerald-900/10 transition-all hover:scale-[1.02]">
                                <i class="fa-regular fa-paper-plane text-sm"></i>
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <!-- No Chat Active Visual Box -->
                <div class="flex-grow flex flex-col items-center justify-center p-8 text-center text-slate-400 h-full">
                    <div class="w-20 h-20 rounded-3xl bg-white shadow-md border border-slate-100 flex items-center justify-center text-emerald-500 mb-5 relative">
                        <i class="fa-regular fa-comments text-4xl"></i>
                        <span class="absolute -top-1.5 -right-1.5 flex h-4 w-4">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500"></span>
                        </span>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">Pesan Obrolan KosinAja</h3>
                    <p class="text-xs text-slate-400 mt-2 max-w-sm leading-relaxed">
                        Pilih mitra bicara Anda dari daftar chat di sebelah kiri untuk melihat riwayat komunikasi atau mengirim pesan baru.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-2.5 justify-center max-w-md">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100 shadow-sm"><i class="fa-solid fa-lock mr-1.5"></i>Ujung-ke-Ujung Aman</span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100 shadow-sm"><i class="fa-solid fa-shield-halved mr-1.5"></i>Proteksi Renter & Owner</span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100 shadow-sm"><i class="fa-solid fa-hourglass-half mr-1.5"></i>Respons Cepat</span>
                    </div>
                </div>
            @endif
        </div>
        
    </div>
</div>

@if(isset($chatPartner))
<script>
    // Scroll chat history to bottom
    const scrollChatToBottom = () => {
        const historyDiv = document.getElementById('chatHistory');
        if (historyDiv) {
            historyDiv.scrollTop = historyDiv.scrollHeight;
        }
    };
    
    // Auto-scroll on load
    window.addEventListener('load', () => {
        setTimeout(scrollChatToBottom, 100);
    });

    // Alpine component helper for state
    function chatSender() {
        return {
            msgText: '',
            imageSelected: false,
            imagePreview: null,
            fileName: '',
            
            previewFile(event) {
                const file = event.target.files[0];
                if (file) {
                    this.imageSelected = true;
                    this.fileName = file.name;
                    
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.imagePreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            },
            
            clearImage() {
                const fileInput = document.querySelector('input[type="file"]');
                if (fileInput) fileInput.value = '';
                this.imageSelected = false;
                this.imagePreview = null;
                this.fileName = '';
            },
            
            sendMessage() {
                const form = document.getElementById('chatForm');
                const formData = new FormData(form);
                
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Append message
                        const historyContainer = document.querySelector('#chatHistory > div');
                        
                        // If it has empty state view, clear it
                        if (historyContainer.querySelector('.py-16')) {
                            historyContainer.innerHTML = '';
                        }
                        
                        historyContainer.insertAdjacentHTML('beforeend', data.html);
                        
                        // Play sent chime sound!
                        playChatChime();
                        
                        // Reset input forms
                        this.msgText = '';
                        this.clearImage();
                        
                        // Scroll
                        scrollChatToBottom();
                    }
                })
                .catch(err => {
                    console.error('Error sending message:', err);
                    // Fallback to legacy form submit if AJAX fails
                    form.submit();
                });
            }
        };
    }

    // Audio synthesis helper for premium chat bubble ping (Web Audio API)
    function playChatChime() {
        try {
            const context = new (window.AudioContext || window.webkitAudioContext)();
            const now = context.currentTime;
            
            const osc = context.createOscillator();
            const gain = context.createGain();
            
            osc.type = 'sine';
            osc.frequency.setValueAtTime(440.00, now); // A4
            osc.frequency.exponentialRampToValueAtTime(659.25, now + 0.12); // E5
            
            gain.gain.setValueAtTime(0.12, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.18);
            
            osc.connect(gain);
            gain.connect(context.destination);
            
            osc.start(now);
            osc.stop(now + 0.18);
        } catch (e) {
            console.warn("Audio Context blocked or unsupported:", e);
        }
    }

    // Dynamic Simulation: Poll for new messages every 3 seconds to feel premium & real-time
    setInterval(() => {
        fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newHistory = doc.getElementById('chatHistory');
            
            if (newHistory) {
                const oldHistory = document.getElementById('chatHistory');
                
                // Track message count for audio alert
                const oldMsgCount = oldHistory.querySelectorAll('.flex.mb-4').length;
                const newMsgCount = newHistory.querySelectorAll('.flex.mb-4').length;
                
                // Only replace if messages count or content length is different to prevent flickering
                if (oldHistory.innerHTML.length !== newHistory.innerHTML.length) {
                    const activeElement = document.activeElement;
                    const isInputFocused = activeElement && activeElement.tagName === 'INPUT';
                    const scrollPos = oldHistory.scrollTop;
                    const isAtBottom = oldHistory.scrollHeight - oldHistory.scrollTop <= oldHistory.clientHeight + 100;
                    
                    oldHistory.innerHTML = newHistory.innerHTML;
                    
                    // If a new message arrived from partner (incoming message!)
                    if (newMsgCount > oldMsgCount) {
                        const lastBubble = newHistory.querySelector('.flex.mb-4:last-child');
                        if (lastBubble && lastBubble.classList.contains('justify-start')) {
                            playChatChime();
                        }
                    }
                    
                    if (isAtBottom) {
                        scrollChatToBottom();
                    } else {
                        oldHistory.scrollTop = scrollPos;
                    }
                }
            }
        })
        .catch(err => console.warn('Polling error:', err));
    }, 4000);
</script>
@endif
@endsection
