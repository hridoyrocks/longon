{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">ড্যাশবোর্ড</h1>
            <p class="text-gray-600">স্বাগতম, {{ auth()->user()->name }}!</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="card bg-gradient text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">ক্রেডিট ব্যালেন্স</p>
                        <p class="text-2xl font-bold">{{ $stats['credits_balance'] }}</p>
                    </div>
                    <div class="text-3xl">💳</div>
                </div>
            </div>

            <div class="card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">মোট ম্যাচ</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_matches'] }}</p>
                    </div>
                    <div class="text-3xl">⚽</div>
                </div>
            </div>

            <div class="card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">লাইভ ম্যাচ</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['live_matches'] }}</p>
                    </div>
                    <div class="text-3xl">🔴</div>
                </div>
            </div>

            <div class="card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">মোট খরচ</p>
                        <p class="text-2xl font-bold text-gray-900">৳{{ $stats['total_spent'] }}</p>
                    </div>
                    <div class="text-3xl">💰</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Matches -->
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold">সাম্প্রতিক ম্যাচ</h2>
                    <a href="{{ route('matches.index') }}" class="text-blue-600 hover:text-blue-800">সব দেখুন</a>
                </div>

                @forelse($recentMatches as $match)
                <div class="flex items-center justify-between py-3 border-b border-gray-200 last:border-b-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="text-green-600">⚽</span>
                        </div>
                        <div>
                            <p class="font-medium">{{ $match->team_a }} vs {{ $match->team_b }}</p>
                            <p class="text-sm text-gray-600">{{ $match->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold">{{ $match->team_a_score }} - {{ $match->team_b_score }}</p>
                        <span class="text-xs px-2 py-1 rounded 
                            @if($match->status === 'live') bg-red-100 text-red-800
                            @elseif($match->status === 'finished') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($match->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-8">কোনো ম্যাচ নেই</p>
                @endforelse
            </div>

            <!-- Recent Transactions -->
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold">সাম্প্রতিক লেনদেন</h2>
                    <a href="{{ route('credits.history') }}" class="text-blue-600 hover:text-blue-800">সব দেখুন</a>
                </div>

                @forelse($recentTransactions as $transaction)
                <div class="flex items-center justify-between py-3 border-b border-gray-200 last:border-b-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center
                            @if($transaction->transaction_type === 'credit') bg-green-100 text-green-600
                            @else bg-red-100 text-red-600 @endif">
                            <span>{{ $transaction->transaction_type === 'credit' ? '+' : '-' }}</span>
                        </div>
                        <div>
                            <p class="font-medium">{{ $transaction->description }}</p>
                            <p class="text-sm text-gray-600">{{ $transaction->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold {{ $transaction->transaction_type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $transaction->transaction_type === 'credit' ? '+' : '-' }}{{ $transaction->credits_used }}
                        </p>
                        <p class="text-sm text-gray-600">ব্যালেন্স: {{ $transaction->balance_after }}</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-8">কোনো লেনদেন নেই</p>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('matches.create') }}" class="card hover:shadow-lg transition-shadow text-center">
                <div class="text-4xl mb-2">⚽</div>
                <h3 class="font-semibold">নতুন ম্যাচ</h3>
                <p class="text-sm text-gray-600">একটি নতুন ম্যাচ তৈরি করুন</p>
            </a>

            <a href="{{ route('credits.purchase') }}" class="card hover:shadow-lg transition-shadow text-center">
                <div class="text-4xl mb-2">💳</div>
                <h3 class="font-semibold">ক্রেডিট কিনুন</h3>
                <p class="text-sm text-gray-600">আরও ক্রেডিট কিনুন</p>
            </a>

            <a href="{{ route('matches.index') }}" class="card hover:shadow-lg transition-shadow text-center">
                <div class="text-4xl mb-2">📊</div>
                <h3 class="font-semibold">ম্যাচ দেখুন</h3>
                <p class="text-sm text-gray-600">সব ম্যাচ দেখুন</p>
            </a>
        </div>
    </div>
</x-app-layout>