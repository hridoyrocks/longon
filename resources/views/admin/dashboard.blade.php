{{-- resources/views/admin/dashboard.blade.php - Modern Professional Design --}}
<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
        <!-- Header Section -->
        <div class="bg-white/80 backdrop-blur-sm border-b border-white/20 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">
                            অ্যাডমিন ড্যাশবোর্ড
                        </h1>
                        <p class="text-slate-600 mt-1">সিস্টেম পরিচালনা ও নিয়ন্ত্রণ প্যানেল</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="bg-white/60 backdrop-blur-sm rounded-full px-4 py-2 border border-white/20">
                            <span class="text-sm text-slate-600">{{ now()->format('d M Y') }}</span>
                        </div>
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Users -->
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
                                <p class="text-sm text-slate-500 font-medium">মোট ব্যবহারকারী</p>
                                <p class="text-3xl font-bold text-slate-900">{{ number_format($stats['total_users']) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="text-green-600 font-medium">+12%</span>
                            <span class="text-slate-500 ml-1">এই মাসে</span>
                        </div>
                    </div>
                </div>

                <!-- Pending Payments -->
                <div class="group relative">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-amber-500 to-orange-600 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-300"></div>
                    <div class="relative bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/90 transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-500 font-medium">অপেক্ষমান পেমেন্ট</p>
                                <p class="text-3xl font-bold text-slate-900">{{ number_format($stats['pending_payments']) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="text-amber-600 font-medium">{{ $stats['pending_payments'] > 0 ? 'নিয়ন্ত্রণ প্রয়োজন' : 'সব আপডেট' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Total Matches -->
                <div class="group relative">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-300"></div>
                    <div class="relative bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/90 transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-500 font-medium">মোট ম্যাচ</p>
                                <p class="text-3xl font-bold text-slate-900">{{ number_format($stats['total_matches']) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="text-emerald-600 font-medium">+8%</span>
                            <span class="text-slate-500 ml-1">এই সপ্তাহে</span>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="group relative">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-500 to-pink-600 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-300"></div>
                    <div class="relative bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/90 transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-500 font-medium">মোট আয়</p>
                                <p class="text-3xl font-bold text-slate-900">৳{{ number_format($stats['total_revenue']) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="text-purple-600 font-medium">+24%</span>
                            <span class="text-slate-500 ml-1">এই মাসে</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Recent Payments -->
                <div class="lg:col-span-2">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-white/20 p-6 hover:bg-white/90 transition-all duration-300">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-slate-900">অপেক্ষমান পেমেন্ট</h2>
                            <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 transform hover:scale-105">
                                <span class="text-sm font-medium">সব দেখুন</span>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>

                        <div class="space-y-4">
                            @forelse($recentPayments as $payment)
                            <div class="flex items-center justify-between p-4 bg-slate-50/50 rounded-xl border border-slate-200/50 hover:bg-slate-50 transition-all duration-200">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                        <span class="text-white font-semibold">{{ strtoupper(substr($payment->user->name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $payment->user->name }}</p>
                                        <p class="text-sm text-slate-500">{{ $payment->user->email }}</p>
                                        <p class="text-xs text-slate-400">{{ $payment->created_at->format('d M Y, h:i A') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-slate-900">৳{{ number_format($payment->amount) }}</p>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ strtoupper($payment->payment_method) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-2 mt-2">
                                        <button onclick="approvePayment({{ $payment->id }})" class="px-3 py-1 bg-emerald-500 text-white rounded-lg text-xs hover:bg-emerald-600 transition-colors">
                                            অনুমোদন
                                        </button>
                                        <button onclick="rejectPayment({{ $payment->id }})" class="px-3 py-1 bg-red-500 text-white rounded-lg text-xs hover:bg-red-600 transition-colors">
                                            প্রত্যাখ্যান
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-slate-500 font-medium">কোনো অপেক্ষমান পেমেন্ট নেই</p>
                                <p class="text-sm text-slate-400">সব পেমেন্ট প্রক্রিয়া সম্পন্ন</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Quick Actions & Stats -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-white/20 p-6 hover:bg-white/90 transition-all duration-300">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">দ্রুত কর্ম</h3>
                        <div class="space-y-3">
                            <a href="{{ route('admin.payments.index') }}" class="flex items-center p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl hover:from-blue-100 hover:to-indigo-100 transition-all duration-200 group">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="font-medium text-slate-900">পেমেন্ট ব্যবস্থাপনা</p>
                                    <p class="text-sm text-slate-500">পেমেন্ট অনুমোদন ও প্রত্যাখ্যান</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.users.index') }}" class="flex items-center p-3 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl hover:from-emerald-100 hover:to-teal-100 transition-all duration-200 group">
                                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="font-medium text-slate-900">ব্যবহারকারী ব্যবস্থাপনা</p>
                                    <p class="text-sm text-slate-500">ব্যবহারকারী নিয়ন্ত্রণ</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.matches.index') }}" class="flex items-center p-3 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl hover:from-purple-100 hover:to-pink-100 transition-all duration-200 group">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="font-medium text-slate-900">ম্যাচ ব্যবস্থাপনা</p>
                                    <p class="text-sm text-slate-500">ম্যাচ পরিচালনা</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- System Status -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-white/20 p-6 hover:bg-white/90 transition-all duration-300">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">সিস্টেম স্ট্যাটাস</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-600">সার্ভার স্ট্যাটাস</span>
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></div>
                                    <span class="text-sm text-emerald-600 font-medium">অনলাইন</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-600">ডেটাবেজ</span>
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></div>
                                    <span class="text-sm text-emerald-600 font-medium">সক্রিয়</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-600">ক্যাশ</span>
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-amber-500 rounded-full mr-2"></div>
                                    <span class="text-sm text-amber-600 font-medium">৮৫%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function approvePayment(paymentId) {
            if (confirm('আপনি কি এই পেমেন্টটি অনুমোদন করতে চান?')) {
                fetch(`/admin/payments/${paymentId}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('অনুমোদনে সমস্যা হয়েছে');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('অনুমোদনে সমস্যা হয়েছে');
                });
            }
        }

        function rejectPayment(paymentId) {
            const reason = prompt('প্রত্যাখ্যানের কারণ লিখুন:');
            if (reason) {
                fetch(`/admin/payments/${paymentId}/reject`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        admin_notes: reason
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('প্রত্যাখ্যানে সমস্যা হয়েছে');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('প্রত্যাখ্যানে সমস্যা হয়েছে');
                });
            }
        }

        // Auto-refresh every 30 seconds
        setInterval(() => {
            location.reload();
        }, 30000);
    </script>
</x-app-layout>