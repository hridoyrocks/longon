<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">আমার ম্যাচসমূহ</h1>
                <p class="text-gray-600">আপনার সব ম্যাচ এখানে দেখুন এবং পরিচালনা করুন</p>
            </div>
            <a href="{{ route('matches.create') }}" class="btn-primary">
                নতুন ম্যাচ তৈরি করুন
            </a>
        </div>

        <!-- Match Filter -->
        <div class="card mb-6">
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">ফিল্টার:</label>
                    <select class="border border-gray-300 rounded-md px-3 py-1 text-sm">
                        <option value="">সব ম্যাচ</option>
                        <option value="live">লাইভ</option>
                        <option value="finished">সমাপ্ত</option>
                        <option value="pending">অপেক্ষমান</option>
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">সার্চ:</label>
                    <input type="text" placeholder="দল খুঁজুন..." class="border border-gray-300 rounded-md px-3 py-1 text-sm">
                </div>
            </div>
        </div>

        <!-- Matches Grid -->
        @if($matches->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($matches as $match)
            <div class="card hover:shadow-lg transition-shadow">
                <!-- Match Header -->
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs px-2 py-1 rounded-full font-medium
                        @if($match->status === 'live') bg-red-100 text-red-800
                        @elseif($match->status === 'finished') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800 @endif">
                        @if($match->status === 'live') 🔴 লাইভ
                        @elseif($match->status === 'finished') ✅ সমাপ্ত
                        @else ⏳ অপেক্ষমান
                        @endif
                    </span>
                    <span class="text-xs text-gray-500">{{ $match->created_at->format('M d, Y') }}</span>
                </div>

                <!-- Teams -->
                <div class="text-center mb-4">
                    <div class="flex items-center justify-center space-x-4">
                        <div class="text-center">
                            <div class="text-sm font-medium text-gray-700">{{ $match->team_a }}</div>
                            <div class="text-3xl font-bold text-blue-600">{{ $match->team_a_score }}</div>
                        </div>
                        <div class="text-gray-400 text-xl">VS</div>
                        <div class="text-center">
                            <div class="text-sm font-medium text-gray-700">{{ $match->team_b }}</div>
                            <div class="text-3xl font-bold text-red-600">{{ $match->team_b_score }}</div>
                        </div>
                    </div>
                </div>

                <!-- Match Info -->
                <div class="flex items-center justify-between text-sm text-gray-600 mb-4">
                    <span>সময়: {{ $match->match_time }}'</span>
                    <span>ইভেন্ট: {{ $match->events->count() }}</span>
                </div>

                <!-- Match Actions -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('matches.control', $match->id) }}" class="btn-primary text-sm">
                        কন্ট্রোল প্যানেল
                    </a>
                    
                    <div class="flex items-center space-x-2">
                        @if($match->overlayTokens->count() > 0)
                        <button onclick="copyOverlayUrl('{{ route('overlay.show', $match->overlayTokens->first()->token) }}')" 
                                class="text-blue-600 hover:text-blue-800 text-sm">
                            ওভারলে কপি
                        </button>
                        @endif
                        
                        <button class="text-gray-600 hover:text-gray-800 text-sm">
                            ⋮
                        </button>
                    </div>
                </div>

                <!-- Winner Badge -->
                @if($match->status === 'finished')
                <div class="mt-2 text-center">
                    @if($match->getWinner() === 'Draw')
                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">ড্র</span>
                    @else
                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">
                        বিজয়ী: {{ $match->getWinner() }}
                    </span>
                    @endif
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $matches->links() }}
        </div>

        @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="text-6xl mb-4">⚽</div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">কোনো ম্যাচ নেই</h3>
            <p class="text-gray-600 mb-4">আপনার প্রথম ম্যাচ তৈরি করুন এবং লাইভ স্কোর ট্র্যাক করা শুরু করুন</p>
            <a href="{{ route('matches.create') }}" class="btn-primary">
                প্রথম ম্যাচ তৈরি করুন
            </a>
        </div>
        @endif
    </div>

    <script>
        function copyOverlayUrl(url) {
            navigator.clipboard.writeText(url).then(function() {
                alert('ওভারলে URL কপি হয়েছে!');
            });
        }
    </script>
</x-app-layout>