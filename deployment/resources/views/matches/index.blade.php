<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">My Matches</h1>
                        <p class="mt-2 text-gray-600">Manage and control all your football scoreboards</p>
                    </div>
                    <a href="{{ route('matches.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create New Match
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input type="text" placeholder="Search matches..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">All Status</option>
                            <option value="live">Live</option>
                            <option value="pending">Pending</option>
                            <option value="finished">Finished</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                        <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div class="flex items-end">
                        <button class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">
                            Apply Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Matches Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($matches as $match)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
                    <!-- Status Badge -->
                    <div class="relative">
                        <div class="h-2 bg-gradient-to-r 
                            @if($match->status === 'live') from-green-400 to-green-600
                            @elseif($match->status === 'finished') from-gray-400 to-gray-600
                            @else from-yellow-400 to-yellow-600
                            @endif">
                        </div>
                        <div class="absolute top-2 right-2">
                            @if($match->status === 'live')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-1 animate-pulse"></span>
                                    LIVE
                                </span>
                            @elseif($match->status === 'finished')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    FINISHED
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    PENDING
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Match Content -->
                    <div class="p-6">
                        <!-- Teams & Score -->
                        <div class="text-center mb-4">
                            <div class="flex items-center justify-center space-x-4">
                                <div class="flex-1 text-right">
                                    <h3 class="font-bold text-lg text-gray-900">{{ $match->team_a }}</h3>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-3xl font-bold text-gray-900">{{ $match->team_a_score }}</span>
                                    <span class="text-xl text-gray-400">-</span>
                                    <span class="text-3xl font-bold text-gray-900">{{ $match->team_b_score }}</span>
                                </div>
                                <div class="flex-1 text-left">
                                    <h3 class="font-bold text-lg text-gray-900">{{ $match->team_b }}</h3>
                                </div>
                            </div>
                            
                            @if($match->status === 'live')
                                <div class="mt-2 text-sm text-gray-600">
                                    <span class="font-mono">{{ sprintf('%02d:%02d', floor($match->match_time), ($match->match_time * 60) % 60) }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Match Info -->
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center justify-between">
                                <span>Created</span>
                                <span class="font-medium">{{ $match->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Events</span>
                                <span class="font-medium">{{ $match->events->count() }}</span>
                            </div>
                            @if($match->is_premium)
                                <div class="flex items-center justify-between">
                                    <span>Type</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-700">
                                        Premium
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-2">
                            <a href="{{ route('matches.control', $match) }}" class="flex-1 bg-indigo-600 text-white text-center py-2 rounded-lg hover:bg-indigo-700 transition font-medium group-hover:shadow-lg">
                                Control Panel
                            </a>
                            <button class="p-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                        <div class="max-w-md mx-auto">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No matches found</h3>
                            <p class="text-gray-600 mb-6">Get started by creating your first match scoreboard</p>
                            <a href="{{ route('matches.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create First Match
                            </a>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($matches->hasPages())
            <div class="mt-8">
                {{ $matches->links() }}
            </div>
            @endif
        </div>
    </div>

    <style>
        /* Custom scrollbar for filters */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</x-app-layout>
