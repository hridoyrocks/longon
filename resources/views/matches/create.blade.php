<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-900">Create New Match</h1>
                <p class="mt-2 text-gray-600">Set up your football scoreboard in seconds</p>
            </div>

            <!-- Progress Steps -->
            <div class="mb-8">
                <div class="flex items-center justify-center">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold">1</div>
                            <span class="ml-2 text-sm font-medium text-gray-900">Match Details</span>
                        </div>
                        <div class="w-16 h-1 bg-gray-200"></div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center font-bold">2</div>
                            <span class="ml-2 text-sm font-medium text-gray-400">Settings</span>
                        </div>
                        <div class="w-16 h-1 bg-gray-200"></div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center font-bold">3</div>
                            <span class="ml-2 text-sm font-medium text-gray-400">Complete</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Credit Check -->
            @if(!auth()->user()->hasCredits(1))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Insufficient Credits</h3>
                        <p class="mt-1 text-sm text-red-700">You need at least 1 credit to create a match. Your current balance is {{ auth()->user()->credits_balance }} credits.</p>
                        <a href="{{ route('credits.purchase') }}" class="mt-2 inline-flex items-center text-sm font-medium text-red-600 hover:text-red-700">
                            Purchase Credits →
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Main Form -->
            <form method="POST" action="{{ route('matches.store') }}" class="space-y-6">
                @csrf
                
                <!-- Match Details Card -->
                <div class="bg-white rounded-xl shadow-sm p-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Match Information
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Team A -->
                        <div>
                            <label for="team_a" class="block text-sm font-medium text-gray-700 mb-2">
                                Home Team
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-2xl">🏠</span>
                                </div>
                                <input type="text" 
                                       name="team_a" 
                                       id="team_a" 
                                       value="{{ old('team_a') }}"
                                       class="pl-12 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('team_a') border-red-500 @enderror" 
                                       placeholder="Enter home team name"
                                       required>
                            </div>
                            @error('team_a')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Team B -->
                        <div>
                            <label for="team_b" class="block text-sm font-medium text-gray-700 mb-2">
                                Away Team
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-2xl">✈️</span>
                                </div>
                                <input type="text" 
                                       name="team_b" 
                                       id="team_b" 
                                       value="{{ old('team_b') }}"
                                       class="pl-12 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('team_b') border-red-500 @enderror" 
                                       placeholder="Enter away team name"
                                       required>
                            </div>
                            @error('team_b')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tournament Name -->
                    <div class="mt-6">
                        <label for="tournament_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Tournament Name (Optional)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-2xl">🏆</span>
                            </div>
                            <input type="text" 
                                   name="tournament_name" 
                                   id="tournament_name" 
                                   value="{{ old('tournament_name') }}"
                                   class="pl-12 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" 
                                   placeholder="Enter tournament name (e.g., World Cup 2025)">
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="mt-8 p-6 bg-gray-50 rounded-lg">
                        <p class="text-sm font-medium text-gray-700 mb-3">Preview:</p>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <p id="preview_tournament" class="text-center text-sm font-medium text-gray-600 mb-2">Tournament Name</p>
                            <div class="flex items-center justify-center space-x-4">
                                <span id="preview_team_a" class="font-bold text-lg text-gray-900">Home Team</span>
                                <span class="text-2xl font-bold text-gray-700">0</span>
                                <span class="text-xl text-gray-400">-</span>
                                <span class="text-2xl font-bold text-gray-700">0</span>
                                <span id="preview_team_b" class="font-bold text-lg text-gray-900">Away Team</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Options Card -->
                <div class="bg-white rounded-xl shadow-sm p-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Match Settings
                    </h2>
                    
                    <div class="space-y-4">
                        <label class="flex items-center space-x-3 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                            <input type="checkbox" name="enable_penalty" class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500">
                            <div>
                                <span class="font-medium text-gray-900">Enable Penalty Shootout</span>
                                <p class="text-sm text-gray-600">Allow penalty shootout option for tie-breaker scenarios</p>
                            </div>
                        </label>
                        
                        <label class="flex items-center space-x-3 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                            <input type="checkbox" name="enable_events" checked class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500">
                            <div>
                                <span class="font-medium text-gray-900">Track Match Events</span>
                                <p class="text-sm text-gray-600">Record goals, cards, substitutions and other match events</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Credit Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-900">Credit Usage</h3>
                            <p class="mt-1 text-sm text-blue-700">Creating this match will use 1 credit from your balance. Current balance: {{ auth()->user()->credits_balance }} credits.</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('matches.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                        ← Cancel
                    </a>
                    <button type="submit" 
                            @if(!auth()->user()->hasCredits(1)) disabled @endif
                            class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                        Create Match
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Live Preview
        document.getElementById('team_a').addEventListener('input', function(e) {
            const preview = document.getElementById('preview_team_a');
            preview.textContent = e.target.value || 'Home Team';
        });

        document.getElementById('team_b').addEventListener('input', function(e) {
            const preview = document.getElementById('preview_team_b');
            preview.textContent = e.target.value || 'Away Team';
        });

        document.getElementById('tournament_name').addEventListener('input', function(e) {
            const preview = document.getElementById('preview_tournament');
            preview.textContent = e.target.value || 'Tournament Name';
            preview.style.display = e.target.value ? 'block' : 'none';
        });

        // Animate form elements on load
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.bg-white.rounded-xl');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</x-app-layout>
