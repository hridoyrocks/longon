{{-- resources/views/reseller/dashboard.blade.php - Modern Reseller Dashboard --}}
<x-app-layout>
    <div class="min-h-screen">
        <!-- Header Section -->
        <div class="bg-white/80 backdrop-blur-sm border-b border-white/20 sticky top-16 z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent bangla-text">
                            রিসেলার ড্যাশবোর্ড
                        </h1>
                        <p class="text-slate-600 mt-1 bangla-text">আপনার বিক্রয় পরিসংখ্যান এবং কমিশন ট্র্যাক করুন</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        @if($currentTier)
                        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 text-white px-4 py-2 rounded-full text-sm font-medium">
                            <span class="bangla-text">{{ $currentTier->name }} টায়ার</span>
                        </div>
                        @endif
                        <button onclick="generateReferralLink()" class="btn-primary">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                            </svg>
                            <span class="bangla-text">রেফারেল লিংক</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <!-- Total Customers -->
                <div class="group relative">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-300"></div>
                    <div class="relative bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/90 transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-500 font-medium bangla-text">মোট কাস্টমার</p>
                                <p class="text-3xl font-bold text-slate-900">{{ $stats['total_customers'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="text-blue-600 font-medium bangla-text">সর্বমোট</span>
                        </div>
                    </div>
                </div>

                <!-- Active Customers -->
                <div class="group relative">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-300"></div>
                    <div class="relative bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/90 transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-500 font-medium bangla-text">সক্রিয় কাস্টমার</p>
                                <p class="text-3xl font-bold text-slate-900">{{ $stats['active_customers'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="text-emerald-600 font-medium bangla-text">সক্রিয়</span>
                        </div>
                    </div>
                </div>

                <!-- Monthly Sales -->
                <div class="group relative">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-500 to-pink-600 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-300"></div>
                    <div class="relative bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/90 transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-500 font-medium bangla-text">এই মাসের বিক্রয়</p>
                                <p class="text-3xl font-bold text-slate-900">৳{{ number_format($stats['monthly_sales']) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="text-purple-600 font-medium">+15%</span>
                            <span class="text-slate-500 ml-1 bangla-text">গত মাসে</span>
                        </div>
                    </div>
                </div>

                <!-- Commission Balance -->
                <div class="group relative">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-orange-500 to-red-600 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-300"></div>
                    <div class="relative bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/90 transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-500 font-medium bangla-text">কমিশন ব্যালেন্স</p>
                                <p class="text-3xl font-bold text-slate-900">৳{{ number_format($stats['commission_balance']) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="text-orange-600 font-medium bangla-text">উপার্জন</span>
                        </div>
                    </div>
                </div>

                <!-- Total Commission -->
                <div class="group relative">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-300"></div>
                    <div class="relative bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/90 transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-500 font-medium bangla-text">মোট কমিশন আয়</p>
                                <p class="text-3xl font-bold text-slate-900">৳{{ number_format($stats['total_commission_earned']) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="text-yellow-600 font-medium bangla-text">সর্বমোট</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Recent Commissions -->
                <div class="lg:col-span-2">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-white/20 p-6 hover:bg-white/90 transition-all duration-300">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-slate-900 bangla-text">সাম্প্রতিক কমিশন</h2>
                            <a href="{{ route('reseller.commissions') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-xl hover:from-purple-600 hover:to-indigo-700 transition-all duration-200 transform hover:scale-105">
                                <span class="text-sm font-medium bangla-text">সব দেখুন</span>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>

                        <div class="space-y-4">
                            @forelse($recentCommissions as $commission)
                            <div class="flex items-center justify-between p-4 bg-slate-50/50 rounded-xl border border-slate-200/50 hover:bg-slate-50 transition-all duration-200">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                        <span class="text-white font-semibold">{{ strtoupper(substr($commission->customer->name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900 bangla-text">{{ $commission->customer->name }}</p>
                                        <p class="text-sm text-slate-500">{{ $commission->customer->email }}</p>
                                        <p class="text-xs text-slate-400">{{ $commission->created_at->format('d M Y, h:i A') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-slate-900">৳{{ number_format($commission->purchase_amount) }}</p>
                                    <p class="text-sm font-bold text-purple-600">৳{{ number_format($commission->commission_amount) }} কমিশন</p>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            @if($commission->status === 'paid') bg-green-100 text-green-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            @if($commission->status === 'paid') ✅ পেইড
                                            @else ⏳ অপেক্ষমান
                                            @endif
                                        </span>
                                        <span class="text-xs text-slate-500">{{ $commission->commission_percentage }}%</span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-slate-500 font-medium bangla-text">কোনো কমিশন নেই</p>
                                <p class="text-sm text-slate-400 bangla-text">আপনার রেফারেল লিংক শেয়ার করুন</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Current Tier -->
                    @if($currentTier)
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-white/20 p-6 hover:bg-white/90 transition-all duration-300">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4 bangla-text">বর্তমান টায়ার</h3>
                        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 text-white p-4 rounded-xl">
                            <h4 class="text-lg font-bold bangla-text">{{ $currentTier->name }}</h4>
                            <div class="mt-2 space-y-1">
                                <p class="text-sm opacity-90">Level 1: {{ $currentTier->level_1_commission }}%</p>
                                <p class="text-sm opacity-90">Level 2: {{ $currentTier->level_2_commission }}%</p>
                                @if($currentTier->max_monthly_sales)
                                <p class="text-sm opacity-90 bangla-text">লক্ষ্য: ৳{{ number_format($currentTier->max_monthly_sales) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Referral Link -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-white/20 p-6 hover:bg-white/90 transition-all duration-300">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4 bangla-text">রেফারেল লিংক</h3>
                        <div class="space-y-3">
                            <div class="flex rounded-lg border border-gray-300 overflow-hidden">
                                <input type="text" id="referral_link" class="flex-1 px-3 py-2 text-sm bg-gray-50 border-0 focus:ring-0" readonly placeholder="রেফারেল লিংক তৈরি করুন">
                                <button onclick="generateReferralLink()" class="px-4 py-2 bg-blue-500 text-white text-sm hover:bg-blue-600 transition-colors bangla-text">
                                    তৈরি করুন
                                </button>
                            </div>
                            <button onclick="copyReferralLink()" class="w-full btn-success text-sm bangla-text">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                কপি করুন
                            </button>
                        </div>
                    </div>

                    <!-- Payout Request -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-white/20 p-6 hover:bg-white/90 transition-all duration-300">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4 bangla-text">পেআউট রিকোয়েস্ট</h3>
                        <form method="POST" action="{{ route('reseller.request-payout') }}">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 bangla-text">পরিমাণ</label>
                                    <input type="number" name="amount" class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           min="100" max="{{ $stats['commission_balance'] }}" placeholder="৳100" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 bangla-text">পেমেন্ট মাধ্যম</label>
                                    <select name="payment_method" class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                        <option value="bkash">bKash</option>
                                        <option value="nagad">Nagad</option>
                                        <option value="rocket">Rocket</option>
                                        <option value="bank">Bank Transfer</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 bangla-text">অ্যাকাউন্ট নম্বর</label>
                                    <input type="text" name="account_number" class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           placeholder="01XXXXXXXXX" required>
                                </div>
                                <button type="submit" class="w-full btn-primary bangla-text">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    পেআউট রিকোয়েস্ট করুন
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function generateReferralLink() {
            fetch('/reseller/generate-referral-link', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('referral_link').value = data.referral_link;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('রেফারেল লিংক তৈরি করতে সমস্যা হয়েছে');
            });
        }

        function copyReferralLink() {
            const linkInput = document.getElementById('referral_link');
            if (linkInput.value) {
                linkInput.select();
                document.execCommand('copy');
                
                // Show success message
                const button = event.target;
                const originalText = button.innerHTML;
                button.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>কপি হয়েছে!';
                button.classList.add('bg-green-500', 'hover:bg-green-600');
                button.classList.remove('bg-emerald-500', 'hover:bg-emerald-600');
                
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.classList.remove('bg-green-500', 'hover:bg-green-600');
                    button.classList.add('bg-emerald-500', 'hover:bg-emerald-600');
                }, 2000);
            } else {
                alert('প্রথমে রেফারেল লিংক তৈরি করুন!');
            }
        }

        // Auto-refresh every 60 seconds
        setInterval(() => {
            // Only refresh if user is active
            if (document.visibilityState === 'visible') {
                location.reload();
            }
        }, 60000);
    </script>
</x-app-layout>