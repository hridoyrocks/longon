{{-- resources/views/dashboard.blade.php - Modern User Dashboard --}}
<x-app-layout>
    <div class="min-h-screen">
        <!-- Header Section -->
        <div class="bg-white/80 backdrop-blur-sm border-b border-white/20 sticky top-16 z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent bangla-text">
                            ড্যাশবোর্ড
                        </h1>
                        <p class="text-slate-600 mt-1 bangla-text">স্বাগতম, {{ auth()->user()->name }}!</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('matches.create') }}" class="btn-primary">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span class="bangla-text">নতুন ম্যাচ</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Credit Balance -->
                <div class="group relative">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-300"></div>
                    <div class="relative bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/90 transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-500 font-medium bangla-text">ক্রেডিট ব্যালেন্স</p>
                                <p class="text-3xl font-bold text-slate-900">{{ $stats['credits_balance'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-emerald-600 font-medium text-sm bangla-text">সক্রিয় ব্যালেন্স</span>
                            <a href="{{ route('credits.purchase') }}" class="text-xs text-blue-600 hover:text-blue-800 bangla-text">কিনুন</a>
                        </div>
                    </div>
                </div>

                <!-- Total Matches -->
                <div class="group relative">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-300"></div>
                    <div class="relative bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/90 transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-500 font-medium bangla-text">মোট ম্যাচ</p>
                                <p class="text-3xl font-bold text-slate-900">{{ $stats['total_matches'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-blue-600 font-medium text-sm bangla-text">সব ম্যাচ</span>
                            <a href="{{ route('matches.index') }}" class="text-xs text-blue-600 hover:text-blue-800 bangla-text">দেখুন</a>
                        </div>
                    </div>
                </div>

                <!-- Live Matches -->
                <div class="group relative">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-red-500 to-pink-600 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-300"></div>
                    <div class="relative bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/90 transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 12.292l-2.829 2.829-2.828-2.829a4 4 0 010-5.657l2.828-2.828a4 4 0 015.657 0l2.828 2.828a4 4 0 010 5.657z"></path>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-500 font-medium bangla-text">লাইভ ম্যাচ</p>
                                <p class="text-3xl font-bold text-slate-900">{{ $stats['live_matches'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            @if($stats['live_matches'] > 0)
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-red-500 rounded-full mr-2 animate-pulse"></div>
                                    <span class="text-red-600 font-medium text-sm bangla-text">চলমান</span>
                                </div>
                            @else
                                <span class="text-slate-500 font-medium text-sm bangla-text">কোনো লাইভ ম্যাচ নেই</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Total Spent -->
                <div class="group relative">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-500 to-pink-600 rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-300"></div>
                    <div class="relative bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/90 transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-500 font-medium bangla-text">মোট খরচ</p>
                                <p class="text-3xl font-bold text-slate-900">৳{{ number_format($stats['total_spent']) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-purple-600 font-medium text-sm bangla-text">সর্বমোট</span>
                            <a href="{{ route('credits.history') }}" class="text-xs text-blue-600 hover:text-blue-800 bangla-text">হিস্টরি</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Recent Matches -->
                <div class="lg:col-span-2">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-white/20 p-6 hover:bg-white/90 transition-all duration-300">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-slate-900 bangla-text">সাম্প্রতিক ম্যাচ</h2>
                            <a href="{{ route('matches.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 transform hover:scale-105">
                                <span class="text-sm font-medium bangla-text">সব দেখুন</span>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>

                        <div class="space-y-4">
                            @forelse($recentMatches as $match)
                            <div class="flex items-center justify-between p-4 bg-slate-50/50 rounded-xl border border-slate-200/50 hover:bg-slate-50 transition-all duration-200">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900 bangla-text">{{ $match->team_a }} vs {{ $match->team_b }}</p>
                                        <p class="text-sm text-slate-500">{{ $match->created_at->format('d M Y, h:i A') }}</p>
                                        <div class="flex items-center space-x-2 mt-1">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                @if($match->status === 'live') bg-red-100 text-red-800
                                                @elseif($match->status === 'finished') bg-green-100 text-green-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                @if($match->status === 'live') 🔴 লাইভ
                                                @elseif($match->status === 'finished') ✅ সমাপ্ত
                                                @else ⏳ অপেক্ষমান
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="flex items-center space-x-2 text-2xl font-bold text-slate-900">
                                        <span>{{ $match->team_a_score }}</span>
                                        <span class="text-slate-400">-</span>
                                        <span>{{ $match->team_b_score }}</span>
                                    </div>
                                    <p class="text-sm text-slate-500 bangla-text">{{ $match->match_time }}'</p>
                                    <div class="flex items-center space-x-2 mt-2">
                                        <a href="{{ route('matches.control', $match->id) }}" class="px-3 py-1 bg-blue-500 text-white rounded-lg text-xs hover:bg-blue-600 transition-colors bangla-text">
                                            কন্ট্রোল
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <p class="text-slate-500 font-medium bangla-text">কোনো ম্যাচ নেই</p>
                                <p class="text-sm text-slate-400 bangla-text">আপনার প্রথম ম্যাচ তৈরি করুন</p>
                                <a href="{{ route('matches.create') }}" class="mt-4 btn-primary bangla-text">
                                    প্রথম ম্যাচ তৈরি করুন
                                </a>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Recent Transactions -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-white/20 p-6 hover:bg-white/90 transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-slate-900 bangla-text">সাম্প্রতিক লেনদেন</h3>
                            <a href="{{ route('credits.history') }}" class="text-sm text-blue-600 hover:text-blue-800 bangla-text">সব দেখুন</a>
                        </div>

                        <div class="space-y-3">
                            @forelse($recentTransactions as $transaction)
                            <div class="flex items-center justify-between p-3 bg-slate-50/50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center
                                        @if($transaction->transaction_type === 'credit') bg-green-100 text-green-600
                                        @else bg-red-100 text-red-600 @endif">
                                        <span class="text-sm font-bold">{{ $transaction->transaction_type === 'credit' ? '+' : '-' }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-900 bangla-text">{{ $transaction->description }}</p>
                                        <p class="text-xs text-slate-500">{{ $transaction->created_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold {{ $transaction->transaction_type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction->transaction_type === 'credit' ? '+' : '-' }}{{ $transaction->credits_used }}
                                    </p>
                                    <p class="text-xs text-slate-500 bangla-text">ব্যালেন্স: {{ $transaction->balance_after }}</p>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <p class="text-slate-500 text-sm bangla-text">কোনো লেনদেন নেই</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-white/20 p-6 hover:bg-white/90 transition-all duration-300">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4 bangla-text">দ্রুত কর্ম</h3>
                        <div class="space-y-3">
                            <a href="{{ route('matches.create') }}" class="flex items-center p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl hover:from-blue-100 hover:to-indigo-100 transition-all duration-200 group">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="font-medium text-slate-900 bangla-text">নতুন ম্যাচ</p>
                                    <p class="text-sm text-slate-500 bangla-text">ম্যাচ তৈরি করুন</p>
                                </div>
                            </a>

                            <a href="{{ route('credits.purchase') }}" class="flex items-center p-3 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl hover:from-emerald-100 hover:to-teal-100 transition-all duration-200 group">
                                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="font-medium text-slate-900 bangla-text">ক্রেডিট কিনুন</p>
                                    <p class="text-sm text-slate-500 bangla-text">আরও ক্রেডিট কিনুন</p>
                                </div>
                            </a>

                            <a href="{{ route('matches.index') }}" class="flex items-center p-3 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl hover:from-purple-100 hover:to-pink-100 transition-all duration-200 group">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="font-medium text-slate-900 bangla-text">ম্যাচ দেখুন</p>
                                    <p class="text-sm text-slate-500 bangla-text">সব ম্যাচ দেখুন</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>