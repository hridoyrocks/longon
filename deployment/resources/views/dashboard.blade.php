<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 mb-8 text-white">
                <h1 class="text-3xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}! 👋</h1>
                <p class="text-indigo-100">Here's your football scoreboard overview</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Matches -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-500">This Month</span>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ $totalMatches }}</div>
                    <div class="text-sm text-gray-600">Total Matches</div>
                </div>

                <!-- Live Matches -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">LIVE</span>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ $liveMatches }}</div>
                    <div class="text-sm text-gray-600">Live Matches</div>
                </div>

                <!-- Credits Balance -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <a href="{{ route('credits.purchase') }}" class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-full font-medium hover:bg-purple-200 transition">Buy More</a>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ auth()->user()->credits_balance }}</div>
                    <div class="text-sm text-gray-600">Credits Balance</div>
                </div>

                <!-- Total Events -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-500">All Time</span>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ $totalEvents }}</div>
                    <div class="text-sm text-gray-600">Match Events</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('matches.create') }}" class="flex items-center space-x-3 p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                        <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">Create Match</div>
                            <div class="text-sm text-gray-600">Start a new scoreboard</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('matches.index') }}" class="flex items-center space-x-3 p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
                        <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">My Matches</div>
                            <div class="text-sm text-gray-600">View all matches</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('credits.purchase') }}" class="flex items-center space-x-3 p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                        <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">Buy Credits</div>
                            <div class="text-sm text-gray-600">Get more credits</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="w-10 h-10 bg-gray-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">Settings</div>
                            <div class="text-sm text-gray-600">Manage account</div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Matches -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Recent Matches</h2>
                        <a href="{{ route('matches.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">View All →</a>
                    </div>
                </div>
                
                <div class="divide-y divide-gray-100">
                    @forelse($recentMatches as $match)
                    <div class="p-6 hover:bg-gray-50 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    @if($match->status === 'live')
                                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                    @elseif($match->status === 'finished')
                                        <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                                    @else
                                        <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                    @endif
                                </div>
                                <div>
                                    <div class="flex items-center space-x-3">
                                        <span class="font-medium text-gray-900">{{ $match->team_a }}</span>
                                        <span class="text-2xl font-bold text-gray-700">{{ $match->team_a_score }}</span>
                                        <span class="text-gray-400">-</span>
                                        <span class="text-2xl font-bold text-gray-700">{{ $match->team_b_score }}</span>
                                        <span class="font-medium text-gray-900">{{ $match->team_b }}</span>
                                    </div>
                                    <div class="flex items-center space-x-4 mt-1">
                                        <span class="text-sm text-gray-500">
                                            @if($match->status === 'live')
                                                <span class="text-green-600 font-medium">● LIVE</span> - {{ floor($match->match_time) }}'
                                            @elseif($match->status === 'finished')
                                                <span class="text-gray-600">Finished</span>
                                            @else
                                                <span class="text-yellow-600">Pending</span>
                                            @endif
                                        </span>
                                        <span class="text-sm text-gray-500">{{ $match->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('matches.control', $match) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium text-sm">
                                    Control
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-12 text-center">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-gray-500 mb-4">No matches created yet</p>
                        <a href="{{ route('matches.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                            Create Your First Match
                        </a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
