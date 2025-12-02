{{-- resources/views/admin/matches.blade.php - Modern Admin Matches Management --}}
<x-app-layout>
    <div class="min-h-screen">
        <!-- Header Section -->
        <div class="bg-white/80 backdrop-blur-sm border-b border-white/20 sticky top-16 z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent bangla-text">
                            ম্যাচ ব্যবস্থাপনা
                        </h1>
                        <p class="text-slate-600 mt-1 bangla-text">সব ম্যাচ দেখুন এবং পরিচালনা করুন</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="bg-white/60 backdrop-blur-sm rounded-full px-4 py-2 border border-white/20">
                            <span class="text-sm text-slate-600 bangla-text">মোট: {{ $matches->total() }} টি</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-red-500 rounded-full mr-2 animate-pulse"></div>
                                <span class="text-sm text-slate-600 bangla-text">{{ $matches->where('status', 'live')->count() }} লাইভ</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Filter Section -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-white/20 p-6 mb-6">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-slate-700 bangla-text">স্ট্যাটাস:</label>
                        <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">সব স্ট্যাটাস</option>
                            <option value="pending">অপেক্ষমান</option>
                            <option value="live">লাইভ</option>
                            <option value="finished">সমাপ্ত</option>
                        </select>
                    </div>
                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-slate-700 bangla-text">তারিখ:</label>
                        <input type="date" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-slate-700 bangla-text">সার্চ:</label>
                        <input type="text" placeholder="দল খুঁজুন..." class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <button class="btn-primary text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span class="bangla-text">খুঁজুন</span>
                    </button>
                </div>
            </div>

            <!-- Matches Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($matches as $match)
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-white/20 p-6 hover:bg-white/90 transition-all duration-300 card-hover">
                    <!-- Match Header -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                            @if($match->status === 'live') bg-red-100 text-red-800
                            @elseif($match->status === 'finished') bg-green-100 text-green-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            @if($match->status === 'live') 🔴 লাইভ
                            @elseif($match->status === 'finished') ✅ সমাপ্ত
                            @else ⏳ অপেক্ষমান
                            @endif
                        </span>
                        <div class="flex items-center space-x-2">
                            @if($match->is_premium)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                ⭐ প্রিমিয়াম
                            </span>
                            @endif
                            <div class="relative">
                                <button onclick="toggleMatchMenu({{ $match->id }})" class="text-slate-400 hover:text-slate-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                    </svg>
                                </button>
                                <div id="matchMenu{{ $match->id }}" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 hidden">
                                    <div class="py-1">
                                        <a href="#" onclick="viewMatch({{ $match->id }})" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 bangla-text">বিস্তারিত দেখুন</a>
                                        <a href="#" onclick="featureMatch({{ $match->id }})" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 bangla-text">ফিচার করুন</a>
                                        <a href="#" onclick="deleteMatch({{ $match->id }})" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 bangla-text">মুছে দিন</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Teams -->
                    <div class="text-center mb-4">
                        <div class="flex items-center justify-center space-x-4">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mb-2">
                                    <span class="text-white font-bold text-lg">{{ strtoupper(substr($match->team_a, 0, 1)) }}</span>
                                </div>
                                <div class="text-sm font-medium text-slate-700 bangla-text">{{ $match->team_a }}</div>
                                <div class="text-3xl font-bold text-slate-900">{{ $match->team_a_score }}</div>
                            </div>
                            <div class="text-slate-400 text-xl font-bold">VS</div>
                            <div class="text-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-600 rounded-full flex items-center justify-center mb-2">
                                    <span class="text-white font-bold text-lg">{{ strtoupper(substr($match->team_b, 0, 1)) }}</span>
                                </div>
                                <div class="text-sm font-medium text-slate-700 bangla-text">{{ $match->team_b }}</div>
                                <div class="text-3xl font-bold text-slate-900">{{ $match->team_b_score }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Match Info -->
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-500 bangla-text">সময়:</span>
                            <span class="font-medium text-slate-900">{{ $match->match_time }}'</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-500 bangla-text">ইভেন্ট:</span>
                            <span class="font-medium text-slate-900">{{ $match->events->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-500 bangla-text">ব্যবহারকারী:</span>
                            <span class="font-medium text-slate-900">{{ $match->user->name }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-500 bangla-text">তৈরি:</span>
                            <span class="font-medium text-slate-900">{{ $match->created_at->format('d M Y') }}</span>
                        </div>
                    </div>

                    <!-- Winner Badge -->
                    @if($match->status === 'finished')
                    <div class="text-center mb-4">
                        @if($match->getWinner() === 'Draw')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 bangla-text">
                            🤝 ড্র
                        </span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 bangla-text">
                            🏆 বিজয়ী: {{ $match->getWinner() }}
                        </span>
                        @endif
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex items-center justify-between">
                        <button onclick="viewMatch({{ $match->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium bangla-text">
                            বিস্তারিত দেখুন
                        </button>
                        <div class="flex items-center space-x-2">
                            @if($match->overlayTokens->count() > 0)
                            <button onclick="copyOverlayUrl('{{ route('overlay.show', $match->overlayTokens->first()->token) }}')" 
                                    class="text-purple-600 hover:text-purple-800 text-sm font-medium bangla-text">
                                ওভারলে
                            </button>
                            @endif
                            <button onclick="toggleMatchMenu({{ $match->id }})" class="text-slate-400 hover:text-slate-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <p class="text-slate-500 font-medium bangla-text">কোনো ম্যাচ পাওয়া যায়নি</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($matches->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $matches->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Match Details Modal -->
    <div id="matchModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 bangla-text">ম্যাচ বিস্তারিত</h3>
                    <button onclick="hideMatchModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="matchDetails">
                    <!-- Match details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function toggleMatchMenu(matchId) {
            const menu = document.getElementById('matchMenu' + matchId);
            // Hide all other menus
            document.querySelectorAll('[id^="matchMenu"]').forEach(m => {
                if (m.id !== 'matchMenu' + matchId) {
                    m.classList.add('hidden');
                }
            });
            // Toggle current menu
            menu.classList.toggle('hidden');
        }

        function viewMatch(matchId) {
            // Load match details via AJAX
            fetch(`/admin/matches/${matchId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('matchDetails').innerHTML = `
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h4 class="font-medium text-gray-900">${data.team_a}</h4>
                                    <p class="text-3xl font-bold text-blue-600">${data.team_a_score}</p>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">${data.team_b}</h4>
                                    <p class="text-3xl font-bold text-red-600">${data.team_b_score}</p>
                                </div>
                            </div>
                            <div class="border-t pt-4">
                                <h5 class="font-medium text-gray-900 mb-2">ম্যাচ তথ্য</h5>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500">স্ট্যাটাস:</span>
                                        <span class="font-medium">${data.status}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">সময়:</span>
                                        <span class="font-medium">${data.match_time}'</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">ব্যবহারকারী:</span>
                                        <span class="font-medium">${data.user.name}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">তৈরি:</span>
                                        <span class="font-medium">${new Date(data.created_at).toLocaleDateString()}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    document.getElementById('matchModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('ম্যাচ বিস্তারিত লোড করতে সমস্যা হয়েছে');
                });
        }

        function hideMatchModal() {
            document.getElementById('matchModal').classList.add('hidden');
        }

        function featureMatch(matchId) {
            if (confirm('আপনি কি এই ম্যাচটি ফিচার করতে চান?')) {
                fetch(`/admin/matches/${matchId}/feature`, {
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
                        alert('ম্যাচ ফিচার করতে সমস্যা হয়েছে');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('ম্যাচ ফিচার করতে সমস্যা হয়েছে');
                });
            }
        }

        function deleteMatch(matchId) {
            if (confirm('আপনি কি নিশ্চিত যে এই ম্যাচটি মুছে ফেলতে চান?')) {
                fetch(`/admin/matches/${matchId}`, {
                    method: 'DELETE',
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
                        alert('ম্যাচ মুছতে সমস্যা হয়েছে');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('ম্যাচ মুছতে সমস্যা হয়েছে');
                });
            }
        }

        function copyOverlayUrl(url) {
            navigator.clipboard.writeText(url).then(function() {
                // Show success message
                const button = event.target;
                const originalText = button.textContent;
                button.textContent = 'কপি হয়েছে!';
                button.classList.add('text-green-600');
                
                setTimeout(() => {
                    button.textContent = originalText;
                    button.classList.remove('text-green-600');
                    button.classList.add('text-purple-600');
                }, 2000);
            }).catch(function() {
                alert('ওভারলে URL কপি করতে সমস্যা হয়েছে');
            });
        }

        // Hide menus when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('[id^="matchMenu"]') && !event.target.closest('button[onclick*="toggleMatchMenu"]')) {
                document.querySelectorAll('[id^="matchMenu"]').forEach(menu => {
                    menu.classList.add('hidden');
                });
            }
        });
    </script>
</x-app-layout>