<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Kedia Builders & Colonizers | The Jaipur Blueprint')</title>
    <meta name="description" content="@yield('meta_description', 'Pioneering luxury real estate in Jaipur, Rajasthan since 1980. 200+ land development projects, JDA approved luxury villas, plots, and apartments.')">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/assets/favicon.png">

    <!-- Google Fonts: Cormorant Garamond / Playfair Display, Inter, JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN with Spaciaz Architectural Config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        kedia: {
                            navy: '#0E1520',
                            navyLight: '#162132',
                            paper: '#FAF8F5',
                            lime: '#D4E845',
                            gold: '#C8A24B',
                            stone: '#E8E3D8'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'Cormorant Garamond', 'serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #FAF8F5;
            color: #0E1520;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        /* Blueprint grid pattern utility */
        .bg-blueprint-dark {
            background-color: #0E1520;
            background-image: radial-gradient(rgba(212, 232, 69, 0.08) 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .bg-blueprint-light {
            background-color: #FAF8F5;
            background-image: radial-gradient(rgba(14, 21, 32, 0.05) 1px, transparent 1px);
            background-size: 24px 24px;
        }

        /* Clean standard scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #FAF8F5;
        }
        ::-webkit-scrollbar-thumb {
            background: #0E1520;
            border-radius: 4px;
        }

        /* Marquee ticker animation with smooth continuous scroll */
        @keyframes marquee {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-50%); }
        }
        .animate-marquee {
            display: flex;
            flex-wrap: nowrap;
            width: max-content;
            animation: marquee 35s linear infinite;
        }
        .animate-marquee:hover {
            animation-play-state: paused;
        }

        /* Subtly animated pulse dot */
        .pulse-emerald {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-green 2s infinite;
        }
        @keyframes pulse-green {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen flex flex-col antialiased">
    
    <!-- Top Navigation -->
    @include('components.navbar')

    <!-- Flash Alert Toasts -->
    @if(session('success'))
        <div id="flashToast" class="fixed bottom-24 right-6 z-50 bg-[#0E1520] text-white border border-[#D4E845] px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 transition-all">
            <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
            <div class="text-xs font-medium">{{ session('success') }}</div>
            <button onclick="this.parentElement.remove()" class="text-white/50 hover:text-white">{{ __('×') }}</button>
        </div>
    @endif

    @if(session('error'))
        <div id="flashToast" class="fixed bottom-24 right-6 z-50 bg-red-950 text-white border border-red-500 px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4">
            <span class="w-3 h-3 rounded-full bg-red-500"></span>
            <div class="text-xs font-medium">{{ session('error') }}</div>
            <button onclick="this.parentElement.remove()" class="text-white/50 hover:text-white">{{ __('×') }}</button>
        </div>
    @endif

    @if($errors->any())
        <div id="validationToast" role="alert" class="fixed bottom-24 right-6 z-50 max-w-sm bg-red-950 text-white border border-red-500 px-6 py-4 rounded-2xl shadow-2xl flex items-start gap-4">
            <span class="mt-1 w-3 h-3 flex-shrink-0 rounded-full bg-red-500"></span>
            <div class="text-xs font-medium space-y-1">
                <div class="font-bold">{{ __('Please check the form and try again:') }}</div>
                <ul class="list-disc pl-4 space-y-0.5 text-white/80">
                    @foreach($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-white/50 hover:text-white" aria-label="Dismiss">{{ __('×') }}</button>
        </div>
    @endif

    <!-- Main Content Yield -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Global Footer -->
    @include('components.footer')

    <!-- Floating Quick Actions Dock (WhatsApp, Direct Call, Scroll Top) -->
    <div class="fixed bottom-6 right-6 z-40 flex flex-col items-end gap-3 pointer-events-auto">
        
        <!-- Back to Top Button -->
        <button type="button" id="btnScrollTop" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="hidden w-11 h-11 rounded-full bg-white text-[#0E1520] border border-[#0E1520]/20 shadow-lg items-center justify-center text-sm font-bold hover:bg-[#FAF8F5] transition-all transform hover:-translate-y-1" title="Scroll to Top">
            ↑
        </button>

        <!-- Quick WhatsApp Button -->
        <a href="https://wa.me/919829184872?text=Hello%20Kedia%20Builders%2C%20I%20would%20like%20to%20inquire%20about%20your%20Jaipur%20projects." target="_blank" rel="noopener noreferrer" class="flex items-center gap-2.5 px-4 py-2.5 rounded-full bg-[#25D366] text-white font-mono text-xs font-bold shadow-xl hover:bg-[#20ba59] transition-all transform hover:-translate-y-0.5 group" title="Chat on WhatsApp">
            <span class="w-2.5 h-2.5 rounded-full bg-white pulse-emerald"></span>
            <span class="hidden sm:inline">{{ __('WhatsApp Desk') }}</span>
            <span class="text-sm">💬</span>
        </a>

        <!-- Quick Call Button -->
        <a href="tel:+918302500500" class="flex items-center gap-2 px-4 py-2.5 rounded-full bg-[#0E1520] text-white border border-white/20 font-mono text-xs font-bold shadow-xl hover:bg-[#162132] transition-all transform hover:-translate-y-0.5" title="Call Kedia Headquarters">
            <span class="text-[#D4E845]">📞</span>
            <span class="hidden sm:inline">+91 8302 500 500</span>
        </a>
    </div>

    <!-- Shortlist Drawer Component (Persisted in localStorage) -->
    <div id="shortlistDrawer" class="fixed inset-y-0 right-0 z-50 w-full max-w-md bg-white border-l border-[#0E1520]/15 shadow-2xl transform translate-x-full transition-transform duration-300 flex flex-col justify-between">
        <div class="p-6 border-b border-[#0E1520]/10 flex items-center justify-between bg-[#FAF8F5]">
            <div class="flex items-center gap-2">
                <span class="text-rose-500 text-lg">♥</span>
                <h3 class="font-serif text-xl font-bold text-[#0E1520]">{{ __('My Shortlisted Units') }}</h3>
            </div>
            <button type="button" onclick="toggleShortlistDrawer()" class="p-2 text-gray-500 hover:text-black text-xl">
                {{ __('×') }}
            </button>
        </div>

        <div id="shortlistItemsContainer" class="p-6 flex-grow overflow-y-auto space-y-4">
            <!-- Dynamic units rendered via JS -->
            <div class="text-center py-12 text-[#0E1520]/50 font-mono text-xs" id="emptyShortlistNotice">
                {{ __('Your shortlist is currently empty.') }}<br>{{ __('Click the heart icon on any unit or project to save it here for comparison.') }}
            </div>
        </div>

        <div class="p-6 border-t border-[#0E1520]/10 bg-[#FAF8F5] space-y-3">
            <button type="button" onclick="openSiteVisitModal()" class="w-full py-3 rounded-full bg-[#D4E845] hover:bg-[#c2d733] text-[#0E1520] font-bold text-xs uppercase tracking-wider shadow">
                {{ __('Schedule Site Visit for Shortlist →') }}
            </button>
            <button type="button" onclick="clearShortlist()" class="w-full py-2 text-center text-xs text-rose-600 hover:underline font-mono">
                {{ __('Clear Shortlist') }}
            </button>
        </div>
    </div>

    <!-- Global Schedule Site Visit Modal -->
    <div id="siteVisitModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="bg-[#0E1520] border border-[#D4E845]/30 rounded-3xl p-6 sm:p-8 max-w-md w-full text-white shadow-2xl relative" id="siteVisitCard">
            <button type="button" onclick="closeSiteVisitModal()" class="absolute top-5 right-5 text-white/50 hover:text-white p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <div class="text-xs font-mono uppercase tracking-widest text-[#D4E845] mb-1">{{ __('VIP Site Experience') }}</div>
            <h3 class="font-serif text-2xl font-bold text-white mb-2">{{ __('messages.schedule_title') }}</h3>
            <p class="text-xs text-white/60 mb-6">{{ __('messages.schedule_desc') }}</p>

            <form action="{{ route('leads.store') }}" method="POST" class="space-y-4 font-mono text-xs">
                @csrf
                <div>
                    <label class="block text-white/70 uppercase mb-1">{{ __('Full Legal Name *') }}</label>
                    <input type="text" name="name" required placeholder="{{ __('e.g. Rahul Sharma') }}" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white text-sm focus:border-[#D4E845] focus:outline-none">
                </div>
                <div>
                    <label class="block text-white/70 uppercase mb-1">{{ __('Phone Number (WhatsApp) *') }}</label>
                    <input type="tel" name="phone" required placeholder="+91 98290 00000" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white text-sm focus:border-[#D4E845] focus:outline-none">
                </div>
                <div>
                    <label class="block text-white/70 uppercase mb-1">{{ __('Select Project') }}</label>
                    <select name="project_name" class="w-full px-4 py-2.5 rounded-xl bg-[#162132] border border-white/10 text-white text-sm focus:border-[#D4E845] focus:outline-none">
                        <option value="Kedia's Landmark">{{ __('Kedia\'s Landmark (Ajmer Road)') }}</option>
                        <option value="Kedia's Atulyam">{{ __('Kedia\'s Atulyam (Sirsi Road)') }}</option>
                        <option value="Kedia's Tulsi">{{ __('Kedia\'s Tulsi (Bani Park)') }}</option>
                        <option value="Ganpati Greens">{{ __('Ganpati Greens (Sikar Road)') }}</option>
                        <option value="Nikunj Villas">{{ __('Nikunj Villas (Sirsi Road)') }}</option>
                        <option value="Kedia Aravali">{{ __('Kedia Aravali Plots') }}</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-white/70 uppercase mb-1">{{ __('Date') }}</label>
                        <input type="date" name="preferred_date" value="{{ date('Y-m-d', strtotime('+1 day')) }}" class="w-full px-3 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white text-xs focus:border-[#D4E845] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-white/70 uppercase mb-1">{{ __('Slot') }}</label>
                        <select name="time_slot" class="w-full px-3 py-2.5 rounded-xl bg-[#162132] border border-white/10 text-white text-xs focus:border-[#D4E845] focus:outline-none">
                            <option value="10:00 AM - 12:00 PM">{{ __('10:00 AM - 12:00 PM') }}</option>
                            <option value="12:00 PM - 02:00 PM">{{ __('12:00 PM - 02:00 PM') }}</option>
                            <option value="03:00 PM - 05:00 PM">{{ __('03:00 PM - 05:00 PM') }}</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" id="pickup_requested" name="pickup_requested" value="1" class="rounded border-white/20 text-[#D4E845]">
                    <label for="pickup_requested" class="text-xs text-white/80">{{ __('Request Chauffeur Pickup (Jaipur City)') }}</label>
                </div>

                <button type="submit" class="w-full py-3 rounded-full bg-[#D4E845] hover:bg-[#c2d733] text-[#0E1520] font-bold text-xs uppercase tracking-wider transition-all shadow-lg mt-2">
                    {{ __('Confirm Private Site Tour →') }}
                </button>
            </form>
        </div>
    </div>

    <!-- Global AI Concierge Modal -->
    <div id="aiConciergeModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="bg-[#0E1520] border border-[#D4E845]/30 rounded-3xl p-6 sm:p-8 max-w-lg w-full text-white shadow-2xl relative">
            <button type="button" onclick="closeAiConciergeModal()" class="absolute top-5 right-5 text-white/50 hover:text-white p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <div class="flex items-center gap-2 text-xs font-mono uppercase tracking-widest text-[#D4E845] mb-1">
                <span>{{ __('✨ Kedia AI Concierge') }}</span>
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                <span class="text-white/60">{{ __('Live Assistant') }}</span>
            </div>
            <h3 class="font-serif text-2xl font-bold text-white mb-2">{{ __('Find Your Ideal Residence') }}</h3>
            <p class="text-xs text-white/60 mb-6">{{ __('Select your criteria to get instant project and inventory recommendations based on 4+ decades of Jaipur real estate data.') }}</p>

            <div class="space-y-4 text-xs font-mono">
                <div>
                    <label class="block text-white/70 uppercase mb-1">{{ __('Target Budget Range') }}</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" onclick="filterByBudget('30-50')" class="p-2.5 rounded-xl border border-white/10 bg-white/5 hover:border-[#D4E845] hover:text-[#D4E845] text-center">₹ 30 - 50 L</button>
                        <button type="button" onclick="filterByBudget('50-80')" class="p-2.5 rounded-xl border border-white/10 bg-white/5 hover:border-[#D4E845] hover:text-[#D4E845] text-center">₹ 50 - 80 L</button>
                        <button type="button" onclick="filterByBudget('80+')" class="p-2.5 rounded-xl border border-white/10 bg-white/5 hover:border-[#D4E845] hover:text-[#D4E845] text-center">{{ __('₹ 80 L & Above') }}</button>
                    </div>
                </div>

                <div>
                    <label class="block text-white/70 uppercase mb-1">{{ __('Preferred Configuration') }}</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" onclick="filterByType('villa')" class="p-2.5 rounded-xl border border-white/10 bg-white/5 hover:border-[#D4E845] hover:text-[#D4E845] text-center">{{ __('Luxury Villa') }}</button>
                        <button type="button" onclick="filterByType('plot')" class="p-2.5 rounded-xl border border-white/10 bg-white/5 hover:border-[#D4E845] hover:text-[#D4E845] text-center">{{ __('JDA Plot') }}</button>
                        <button type="button" onclick="filterByType('apartment')" class="p-2.5 rounded-xl border border-white/10 bg-white/5 hover:border-[#D4E845] hover:text-[#D4E845] text-center">{{ __('3/4 BHK Flat') }}</button>
                    </div>
                </div>

                <div id="aiRecommendationBox" class="mt-4 p-4 rounded-2xl bg-white/5 border border-white/10 text-xs hidden">
                    <div class="text-[#D4E845] font-bold mb-1" id="aiRecTitle">{{ __('Recommended: Kedia\'s Landmark') }}</div>
                    <div class="text-white/70 leading-relaxed" id="aiRecDesc">{{ __('Located on Ajmer Road with JDA approved plots from ₹ 32.83 Lakh and turnkey villas.') }}</div>
                    <a id="aiRecLink" href="{{ route('projects.show', 'kedias-landmark') }}" class="inline-block mt-3 px-4 py-2 rounded-full bg-[#D4E845] text-[#0E1520] font-bold text-[11px] uppercase tracking-wider">
                        {{ __('Explore Township →') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Scroll Top visibility
        window.addEventListener('scroll', () => {
            const btn = document.getElementById('btnScrollTop');
            if (btn) {
                if (window.scrollY > 400) {
                    btn.classList.remove('hidden');
                    btn.classList.add('flex');
                } else {
                    btn.classList.add('hidden');
                    btn.classList.remove('flex');
                }
            }
        });

        // Modals
        function openSiteVisitModal() {
            const modal = document.getElementById('siteVisitModal');
            if (modal) modal.classList.remove('opacity-0', 'pointer-events-none');
        }
        function closeSiteVisitModal() {
            const modal = document.getElementById('siteVisitModal');
            if (modal) modal.classList.add('opacity-0', 'pointer-events-none');
        }

        function openAiConciergeModal() {
            const modal = document.getElementById('aiConciergeModal');
            if (modal) modal.classList.remove('opacity-0', 'pointer-events-none');
        }
        function closeAiConciergeModal() {
            const modal = document.getElementById('aiConciergeModal');
            if (modal) modal.classList.add('opacity-0', 'pointer-events-none');
        }

        function filterByBudget(range) {
            const box = document.getElementById('aiRecommendationBox');
            const title = document.getElementById('aiRecTitle');
            const desc = document.getElementById('aiRecDesc');
            const link = document.getElementById('aiRecLink');
            box.classList.remove('hidden');

            if (range === '30-50') {
                title.innerText = "Recommended: Kedia's Landmark & Ganpati Greens";
                desc.innerText = "Features 111-150 sq.yd JDA approved plots and compact luxury villas starting from ₹ 32.83 Lakh.";
                link.href = "/projects/kedias-landmark";
            } else if (range === '50-80') {
                title.innerText = "Recommended: Kedia's Atulyam (Sirsi Road)";
                desc.innerText = "Smart living community with 3 BHK duplex villas and underground amenities starting from ₹ 58.00 Lakh.";
                link.href = "/projects/kedias-atulyam";
            } else {
                title.innerText = "Recommended: Kedia's Tulsi (Bani Park)";
                desc.innerText = "Ultra-luxury 3 & 4 BHK residences in prime central Jaipur with rooftop infinity lounge, starting ₹ 1.25 Cr.";
                link.href = "/projects/kedias-tulsi";
            }
        }

        function filterByType(type) {
            const box = document.getElementById('aiRecommendationBox');
            const title = document.getElementById('aiRecTitle');
            const desc = document.getElementById('aiRecDesc');
            const link = document.getElementById('aiRecLink');
            box.classList.remove('hidden');

            if (type === 'villa') {
                title.innerText = "Signature Villas: Nikunj Villas & Atulyam";
                desc.innerText = "Architect-crafted 3 & 4 BHK duplex villas with private terrace, personal lawn, and Vastu compliance.";
                link.href = "/projects/nikunj-villas";
            } else if (type === 'plot') {
                title.innerText = "Clear Title Plots: Kedia Aravali & Landmark";
                desc.innerText = "100% JDA Patta sanctioned residential plots with wide sector asphalt roads and water-harvesting.";
                link.href = "/projects/kedia-aravali";
            } else {
                title.innerText = "Prime Apartments: Kedia's Tulsi (Bani Park)";
                desc.innerText = "Exclusive high-rise luxury apartments with private elevator access and serene Bani Park address.";
                link.href = "/projects/kedias-tulsi";
            }
        }

        // Shortlist Logic
        function toggleShortlistDrawer() {
            const drawer = document.getElementById('shortlistDrawer');
            if (drawer) {
                drawer.classList.toggle('translate-x-full');
            }
        }

        function getShortlist() {
            try {
                return JSON.parse(localStorage.getItem('kedia_shortlist') || '[]');
            } catch(e) {
                return [];
            }
        }

        function updateShortlistUI() {
            const list = getShortlist();
            const badge = document.getElementById('navShortlistCount');
            if (badge) {
                badge.innerText = list.length;
                badge.style.display = list.length > 0 ? 'flex' : 'none';
            }

            const container = document.getElementById('shortlistItemsContainer');
            const emptyNotice = document.getElementById('emptyShortlistNotice');
            if (container) {
                container.innerHTML = '';
                if (list.length === 0) {
                    if (emptyNotice) emptyNotice.style.display = 'block';
                    container.appendChild(emptyNotice);
                } else {
                    if (emptyNotice) emptyNotice.style.display = 'none';
                    list.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'p-4 rounded-2xl bg-[#FAF8F5] border border-[#0E1520]/10 flex items-center justify-between font-mono text-xs';
                        div.innerHTML = `
                            <div>
                                <div class="font-bold text-[#0E1520]">${item.title}</div>
                                <div class="text-[10px] text-[#0E1520]/60">${item.location || 'Jaipur'} · ${item.price || ''}</div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="${item.url}" class="px-3 py-1 rounded-full bg-[#0E1520] text-white text-[10px] font-bold">View</a>
                                <button onclick="removeFromShortlist('${item.id}')" class="text-rose-500 hover:text-rose-700 text-sm font-bold">&times;</button>
                            </div>
                        `;
                        container.appendChild(div);
                    });
                }
            }
        }

        function addToShortlist(item) {
            let list = getShortlist();
            if (!list.find(x => x.id === item.id)) {
                list.push(item);
                localStorage.setItem('kedia_shortlist', JSON.stringify(list));
                updateShortlistUI();
            }
            toggleShortlistDrawer();
        }

        function removeFromShortlist(id) {
            let list = getShortlist().filter(x => x.id !== id);
            localStorage.setItem('kedia_shortlist', JSON.stringify(list));
            updateShortlistUI();
        }

        function clearShortlist() {
            localStorage.removeItem('kedia_shortlist');
            updateShortlistUI();
        }

        document.addEventListener('DOMContentLoaded', updateShortlistUI);
    </script>

    @stack('scripts')
</body>
</html>
