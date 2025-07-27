{{-- resources/views/matches/control.blade.php - Simplified Timer System --}}
<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 bangla-text">ম্যাচ কন্ট্রোল প্যানেল</h1>
            <p class="text-gray-600 bangla-text">{{ $match->team_a }} vs {{ $match->team_b }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Match Control -->
            <div class="lg:col-span-2">
                <!-- Score Control -->
                <div class="card mb-6">
                    <h2 class="text-xl font-semibold mb-4 bangla-text">স্কোর কন্ট্রোল</h2>
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-center">
                            <h3 class="font-semibold text-lg bangla-text">{{ $match->team_a }}</h3>
                            <div class="flex items-center justify-center space-x-2 mt-2">
                                <button onclick="changeScore('team_a', -1)" class="bg-red-500 text-white w-10 h-10 rounded-lg hover:bg-red-600 transition-colors">
                                    <span class="text-xl">-</span>
                                </button>
                                <span id="team_a_score" class="text-4xl font-bold text-blue-600">{{ $match->team_a_score }}</span>
                                <button onclick="changeScore('team_a', 1)" class="bg-green-500 text-white w-10 h-10 rounded-lg hover:bg-green-600 transition-colors">
                                    <span class="text-xl">+</span>
                                </button>
                            </div>
                        </div>

                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-600">VS</div>
                            <div class="mt-2">
                                <span class="text-2xl font-mono bg-gray-100 px-4 py-2 rounded" id="match_time_display">00:00</span>
                            </div>
                        </div>

                        <div class="text-center">
                            <h3 class="font-semibold text-lg bangla-text">{{ $match->team_b }}</h3>
                            <div class="flex items-center justify-center space-x-2 mt-2">
                                <button onclick="changeScore('team_b', -1)" class="bg-red-500 text-white w-10 h-10 rounded-lg hover:bg-red-600 transition-colors">
                                    <span class="text-xl">-</span>
                                </button>
                                <span id="team_b_score" class="text-4xl font-bold text-red-600">{{ $match->team_b_score }}</span>
                                <button onclick="changeScore('team_b', 1)" class="bg-green-500 text-white w-10 h-10 rounded-lg hover:bg-green-600 transition-colors">
                                    <span class="text-xl">+</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Simple Timer Control -->
                <div class="card mb-6">
                    <h2 class="text-xl font-semibold mb-4 bangla-text">টাইমার কন্ট্রোল</h2>
                    
                    <!-- Quick Timer Buttons -->
                    <div class="grid grid-cols-4 gap-2 mb-4">
                        <button onclick="setQuickTime(0)" class="bg-gray-200 hover:bg-gray-300 px-3 py-2 rounded text-sm font-medium">0:00</button>
                        <button onclick="setQuickTime(15)" class="bg-gray-200 hover:bg-gray-300 px-3 py-2 rounded text-sm font-medium">15:00</button>
                        <button onclick="setQuickTime(30)" class="bg-gray-200 hover:bg-gray-300 px-3 py-2 rounded text-sm font-medium">30:00</button>
                        <button onclick="setQuickTime(45)" class="bg-gray-200 hover:bg-gray-300 px-3 py-2 rounded text-sm font-medium">45:00</button>
                        <button onclick="addExtraTime(1)" class="bg-yellow-200 hover:bg-yellow-300 px-3 py-2 rounded text-sm font-medium">45+1</button>
                        <button onclick="addExtraTime(2)" class="bg-yellow-200 hover:bg-yellow-300 px-3 py-2 rounded text-sm font-medium">45+2</button>
                        <button onclick="setQuickTime(60)" class="bg-gray-200 hover:bg-gray-300 px-3 py-2 rounded text-sm font-medium">60:00</button>
                        <button onclick="setQuickTime(90)" class="bg-gray-200 hover:bg-gray-300 px-3 py-2 rounded text-sm font-medium">90:00</button>
                    </div>

                    <!-- Manual Time Input -->
                    <div class="flex items-center space-x-2 mb-4">
                        <input type="number" id="manual_minutes" placeholder="মিনিট" class="w-20 border-gray-300 rounded-md" min="0" max="120" value="0">
                        <span class="text-xl">:</span>
                        <input type="number" id="manual_seconds" placeholder="সেকেন্ড" class="w-20 border-gray-300 rounded-md" min="0" max="59" value="0">
                        <button onclick="setManualTime()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            <span class="bangla-text">সেট করুন</span>
                        </button>
                    </div>

                    <!-- Timer Control Buttons -->
                    <div class="flex items-center space-x-4">
                        <button onclick="startTimer()" id="start_btn" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">
                            <span class="bangla-text">▶ শুরু</span>
                        </button>
                        <button onclick="pauseTimer()" id="pause_btn" class="bg-yellow-500 text-white px-6 py-2 rounded hover:bg-yellow-600" style="display:none;">
                            <span class="bangla-text">⏸ পজ</span>
                        </button>
                        <button onclick="resetTimer()" class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600">
                            <span class="bangla-text">⏹ রিসেট</span>
                        </button>
                    </div>
                </div>

                <!-- Match Status -->
                <div class="card mb-6">
                    <h2 class="text-xl font-semibold mb-4 bangla-text">ম্যাচের অবস্থা</h2>
                    <div class="flex space-x-4 mb-4">
                        <button onclick="updateStatus('pending')" 
                                class="px-4 py-2 rounded {{ $match->status === 'pending' ? 'bg-gray-600 text-white' : 'bg-gray-200' }}">
                            <span class="bangla-text">অপেক্ষমান</span>
                        </button>
                        <button onclick="updateStatus('live')" 
                                class="px-4 py-2 rounded {{ $match->status === 'live' ? 'bg-green-600 text-white' : 'bg-gray-200' }}">
                            <span class="bangla-text">লাইভ</span>
                        </button>
                        <button onclick="updateStatus('finished')" 
                                class="px-4 py-2 rounded {{ $match->status === 'finished' ? 'bg-red-600 text-white' : 'bg-gray-200' }}">
                            <span class="bangla-text">সমাপ্ত</span>
                        </button>
                    </div>
                    <p class="mt-2 text-sm text-gray-600 bangla-text mb-4">
                        বর্তমান অবস্থা: <span class="font-semibold" id="current_status">{{ ucfirst($match->status) }}</span>
                    </p>
                    
                    <!-- Penalty Shootout Toggle -->
                    <div class="mb-4 p-3 bg-gray-50 rounded">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" id="penalty_shootout_enabled" class="mr-2" 
                                   {{ $match->penalty_shootout_enabled ? 'checked' : '' }}
                                   onchange="togglePenaltyShootout()">
                            <span class="text-sm font-medium bangla-text">পেনাল্টি শুটআউট সক্রিয়</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1 bangla-text">ড্র হলে পেনাল্টি শুটআউট অপশন দেখাবে</p>
                        
                        <!-- Quick Access Button -->
                        <button id="quick_penalty_access" onclick="toggleTieBreakerPanel()" 
                                class="mt-2 bg-purple-100 text-purple-700 px-3 py-1 rounded text-xs hover:bg-purple-200 transition-colors" 
                                style="display: none;">
                            <span class="bangla-text">🎯 পেনাল্টি কন্ট্রোলার দেখুন</span>
                        </button>
                    </div>
                    
                    <!-- Winner Selection -->
                    <div id="winner_selection" class="mt-4 p-4 bg-gray-100 rounded" style="display: none;">
                        <h3 class="font-semibold mb-2 bangla-text">ম্যাচ ফলাফল:</h3>
                        
                        <!-- Match Result Display -->
                        <div id="match_result_display" class="mb-3 text-center" style="display: none;">
                            <p class="text-2xl font-bold" id="result_text"></p>
                            <p class="text-lg text-gray-600" id="score_text"></p>
                        </div>
                        
                        <div class="flex space-x-3" id="winner_buttons">
                            <button onclick="declareWinner('team_a')" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                <span class="bangla-text">{{ $match->team_a }} বিজয়ী</span>
                            </button>
                            <button onclick="declareWinner('team_b')" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                <span class="bangla-text">{{ $match->team_b }} বিজয়ী</span>
                            </button>
                            <button onclick="declareWinner('draw')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                <span class="bangla-text">ড্র</span>
                            </button>
                        </div>
                        
                        <!-- Tie-breaker option -->
                        <div id="tie_check" class="mt-4" style="display: none;">
                            <p class="text-sm text-gray-600 mb-2 bangla-text">ম্যাচ টাই হয়েছে। টাই-ব্রেকার শুরু করতে চান?</p>
                            <button onclick="startTieBreaker()" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                                <span class="bangla-text">⚽ টাই-ব্রেকার শুরু করুন</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Player Management Panel -->
                <div class="card mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold bangla-text flex items-center">
                            <span class="text-2xl mr-2">👥</span> প্লেয়ার ম্যানেজমেন্ট
                        </h2>
                        <button onclick="togglePlayerList()" id="player_list_toggle_btn" 
                                class="px-4 py-2 rounded transition-colors {{ $match->show_player_list ? 'bg-green-500 text-white' : 'bg-gray-200' }}">
                            <span class="bangla-text">{{ $match->show_player_list ? '🔴 লাইভ' : 'প্লেয়ার লিস্ট দেখান' }}</span>
                        </button>
                    </div>
                    
                    <!-- Tournament Name Display/Edit -->
                    @if($match->tournament_name)
                    <div class="mb-4 p-3 bg-gray-50 rounded">
                        <p class="text-sm font-medium text-gray-700 bangla-text">টুর্নামেন্ট:</p>
                        <p class="text-lg font-bold">{{ $match->tournament_name }}</p>
                    </div>
                    @endif
                    
                    <!-- Quick Add Player Form -->
                    <div class="mb-4">
                        <h3 class="text-sm font-medium mb-2 bangla-text">দ্রুত প্লেয়ার যোগ করুন:</h3>
                        <form id="quick_player_form" onsubmit="addPlayer(event)" class="flex gap-2">
                            <select name="team" class="border-gray-300 rounded-md text-sm" required>
                                <option value="">টিম</option>
                                <option value="home">{{ $match->team_a }} (Home)</option>
                                <option value="away">{{ $match->team_b }} (Away)</option>
                            </select>
                            <input type="text" name="name" placeholder="নাম" class="flex-1 border-gray-300 rounded-md text-sm" required>
                            <input type="text" name="jersey_number" placeholder="#" class="w-16 border-gray-300 rounded-md text-sm">
                            <select name="position" class="border-gray-300 rounded-md text-sm">
                                <option value="">পজিশন</option>
                                <option value="GK">GK</option>
                                <option value="DEF">DEF</option>
                                <option value="MID">MID</option>
                                <option value="FWD">FWD</option>
                            </select>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm">
                                <span class="bangla-text">যোগ</span>
                            </button>
                        </form>
                    </div>
                    
                    <!-- Player Lists -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Home Team Players -->
                        <div class="bg-blue-50 p-3 rounded">
                            <h4 class="font-semibold text-sm mb-2 text-blue-800">{{ $match->team_a }}</h4>
                            <div id="home_players_list" class="space-y-1 max-h-32 overflow-y-auto">
                                <p class="text-xs text-gray-500 text-center py-2">লোড হচ্ছে...</p>
                            </div>
                        </div>
                        
                        <!-- Away Team Players -->
                        <div class="bg-red-50 p-3 rounded">
                            <h4 class="font-semibold text-sm mb-2 text-red-800">{{ $match->team_b }}</h4>
                            <div id="away_players_list" class="space-y-1 max-h-32 overflow-y-auto">
                                <p class="text-xs text-gray-500 text-center py-2">লোড হচ্ছে...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tie-Breaker Panel -->
                <div id="tie_breaker_panel" class="card mb-6" style="display: none;">
                    <h2 class="text-xl font-semibold mb-4 bangla-text flex items-center justify-between">
                        <span class="flex items-center">
                            <span class="text-2xl mr-2">⚽</span> টাই-ব্রেকার (পেনাল্টি শুটআউট)
                        </span>
                        <span class="text-sm font-normal bg-gray-200 px-3 py-1 rounded">
                            মোট পেনাল্টি: <span id="total_penalties">0</span>
                        </span>
                    </h2>
                    
                    <!-- Current Score Summary -->
                    <div class="bg-gradient-to-r from-blue-50 to-red-50 p-4 rounded-lg mb-4 text-center">
                        <div class="flex justify-center items-center space-x-8">
                            <div>
                                <p class="text-sm text-gray-600 bangla-text">{{ $match->team_a }}</p>
                                <p class="text-4xl font-bold text-blue-600" id="team_a_score_big">0</p>
                            </div>
                            <div class="text-4xl font-bold text-gray-400">-</div>
                            <div>
                                <p class="text-sm text-gray-600 bangla-text">{{ $match->team_b }}</p>
                                <p class="text-4xl font-bold text-red-600" id="team_b_score_big">0</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2" id="penalty_round_info">রাউন্ড ১ (৫ পেনাল্টি)</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <!-- Team A Penalties -->
                        <div class="bg-blue-50 p-4 rounded-lg border-2 border-blue-200">
                            <h3 class="font-bold text-lg text-center mb-3 text-blue-800">{{ $match->team_a }}</h3>
                            <div class="flex justify-center items-center mb-4">
                                <span class="text-5xl font-bold text-blue-600" id="team_a_penalties">0</span>
                                <span class="text-2xl text-gray-600 ml-2">/ <span id="team_a_attempts">0</span></span>
                            </div>
                            <div id="team_a_penalty_shots" class="flex justify-center space-x-2 mb-4">
                                <!-- Penalty indicators will be added here -->
                            </div>
                            <div class="flex justify-center space-x-2">
                                <button onclick="addPenalty('team_a', true)" class="bg-green-500 text-white px-4 py-2 rounded-full hover:bg-green-600 transition-all transform hover:scale-105">
                                    <span class="text-xl">✓</span> গোল
                                </button>
                                <button onclick="addPenalty('team_a', false)" class="bg-red-500 text-white px-4 py-2 rounded-full hover:bg-red-600 transition-all transform hover:scale-105">
                                    <span class="text-xl">×</span> মিস
                                </button>
                            </div>
                            <!-- Penalty History -->
                            <div class="mt-3 text-xs text-gray-600">
                                <p class="bangla-text">সফল রেট: <span id="team_a_success_rate">0%</span></p>
                            </div>
                        </div>
                        
                        <!-- Team B Penalties -->
                        <div class="bg-red-50 p-4 rounded-lg border-2 border-red-200">
                            <h3 class="font-bold text-lg text-center mb-3 text-red-800">{{ $match->team_b }}</h3>
                            <div class="flex justify-center items-center mb-4">
                                <span class="text-5xl font-bold text-red-600" id="team_b_penalties">0</span>
                                <span class="text-2xl text-gray-600 ml-2">/ <span id="team_b_attempts">0</span></span>
                            </div>
                            <div id="team_b_penalty_shots" class="flex justify-center space-x-2 mb-4">
                                <!-- Penalty indicators will be added here -->
                            </div>
                            <div class="flex justify-center space-x-2">
                                <button onclick="addPenalty('team_b', true)" class="bg-green-500 text-white px-4 py-2 rounded-full hover:bg-green-600 transition-all transform hover:scale-105">
                                    <span class="text-xl">✓</span> গোল
                                </button>
                                <button onclick="addPenalty('team_b', false)" class="bg-red-500 text-white px-4 py-2 rounded-full hover:bg-red-600 transition-all transform hover:scale-105">
                                    <span class="text-xl">×</span> মিস
                                </button>
                            </div>
                            <!-- Penalty History -->
                            <div class="mt-3 text-xs text-gray-600">
                                <p class="bangla-text">সফল রেট: <span id="team_b_success_rate">0%</span></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tie-breaker Status -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600 bangla-text" id="tie_breaker_status">প্রথম ৫টি কিক চলছে...</p>
                        
                        <!-- Penalty Sequence Display -->
                        <div class="mt-4 p-3 bg-gray-100 rounded">
                            <p class="text-xs text-gray-600 bangla-text mb-2">পেনাল্টি ক্রম:</p>
                            <div id="penalty_sequence" class="flex justify-center flex-wrap gap-2">
                                <!-- Penalty sequence will be shown here -->
                            </div>
                        </div>
                        
                        <div class="flex justify-center space-x-3 mt-3">
                            <button onclick="endTieBreaker()" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                                <span class="bangla-text">টাই-ব্রেকার শেষ করুন</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Add Event -->
                <div class="card">
                    <h2 class="text-xl font-semibold mb-4 bangla-text">ইভেন্ট যোগ করুন</h2>
                    <form id="event_form" onsubmit="addEvent(event)">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 bangla-text">ইভেন্ট টাইপ</label>
                                <select name="event_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="goal">গোল</option>
                                    <option value="yellow_card">হলুদ কার্ড</option>
                                    <option value="red_card">লাল কার্ড</option>
                                    <option value="substitution">খেলোয়াড় পরিবর্তন</option>
                                    <option value="penalty">পেনাল্টি</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 bangla-text">টিম</label>
                                <select name="team" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="team_a">{{ $match->team_a }}</option>
                                    <option value="team_b">{{ $match->team_b }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 bangla-text">খেলোয়াড়</label>
                                <input type="text" name="player" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="খেলোয়াড়ের নাম">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 bangla-text">মিনিট</label>
                                <div class="relative">
                                    <input type="number" name="minute" id="event_minute" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm pr-20" min="0" max="120">
                                    <button type="button" onclick="setCurrentTimeInEvent()" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-gray-500 text-white text-xs px-2 py-1 rounded hover:bg-gray-600">
                                        <span class="bangla-text">লাইভ</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                <span class="bangla-text">ইভেন্ট যোগ করুন</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Overlay Link -->
                <div class="card">
                    <h2 class="text-xl font-semibold mb-4 bangla-text">ওভারলে লিংক</h2>
                    @if($overlayToken)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 bangla-text">ওভারলে URL</label>
                        <div class="mt-1 flex">
                            <input type="text" id="overlay_url" value="{{ route('overlay.show', $overlayToken->token) }}" 
                                   class="flex-1 border-gray-300 rounded-l-md shadow-sm text-sm" readonly>
                            <button onclick="copyOverlayUrl()" class="bg-blue-500 text-white px-4 py-2 rounded-r-md hover:bg-blue-600">
                                <span class="bangla-text">কপি</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Events Ticker Toggle -->
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" id="show_events_ticker" class="mr-2" onchange="updateOverlaySettings()">
                            <span class="text-sm bangla-text">ইভেন্ট টিকার দেখান</span>
                        </label>
                    </div>
                    @endif
                    <button onclick="generateOverlayLink()" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        <span class="bangla-text">{{ $overlayToken ? 'নতুন লিংক তৈরি করুন' : 'ওভারলে লিংক তৈরি করুন' }}</span>
                    </button>
                </div>

                <!-- Match Events -->
                <div class="card">
                    <h2 class="text-xl font-semibold mb-4 bangla-text">ম্যাচ ইভেন্ট</h2>
                    <div id="events_list" class="space-y-3 max-h-96 overflow-y-auto">
                        @forelse($events as $event)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium text-sm">{{ $event->minute }}'</span>
                                    <span class="text-sm text-gray-600">{{ $event->event_type }}</span>
                                </div>
                                <p class="text-sm text-gray-800 bangla-text">{{ $event->player ?: 'Unknown' }}</p>
                                <p class="text-xs text-gray-500 bangla-text">{{ $event->team === 'team_a' ? $match->team_a : $match->team_b }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center py-4 bangla-text">কোনো ইভেন্ট নেই</p>
                        @endforelse
                    </div>
                </div>

                <!-- Connection Status -->
                <div class="card">
                    <h2 class="text-xl font-semibold mb-4 bangla-text">কানেকশন স্ট্যাটাস</h2>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <div id="pusher_status" class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                            <span class="text-sm bangla-text" id="pusher_status_text">Disconnected</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Penalty Shootout Bottom Bar (Removed - keeping only main panel controller) -->
    <!--
    <div id="penalty_bottom_bar" class="fixed bottom-0 left-0 right-0 bg-gray-900 text-white shadow-2xl transform translate-y-full transition-transform duration-500" style="z-index: 9999;">
        ...
    </div>
    -->

    <!-- Penalty Mode Quick Controls -->
    <div id="penalty_quick_controls" class="fixed bottom-16 right-4 bg-white rounded-lg shadow-xl p-3" style="display: none; z-index: 9998;">
        <h4 class="text-sm font-bold mb-2 bangla-text">পেনাল্টি কন্ট্রোল</h4>
        <div class="space-y-2">
            <div class="flex space-x-2">
                <button onclick="quickPenalty('team_a', true)" class="bg-blue-500 text-white px-3 py-1 rounded text-xs flex-1">
                    {{ substr($match->team_a, 0, 3) }} ✓
                </button>
                <button onclick="quickPenalty('team_a', false)" class="bg-gray-500 text-white px-3 py-1 rounded text-xs flex-1">
                    {{ substr($match->team_a, 0, 3) }} ×
                </button>
            </div>
            <div class="flex space-x-2">
                <button onclick="quickPenalty('team_b', true)" class="bg-red-500 text-white px-3 py-1 rounded text-xs flex-1">
                    {{ substr($match->team_b, 0, 3) }} ✓
                </button>
                <button onclick="quickPenalty('team_b', false)" class="bg-gray-500 text-white px-3 py-1 rounded text-xs flex-1">
                    {{ substr($match->team_b, 0, 3) }} ×
                </button>
            </div>
        </div>
        <button onclick="toggleQuickControls()" class="mt-2 text-xs text-gray-500 hover:text-gray-700">
            × বন্ধ
        </button>
    </div>

    <!-- Notification Container -->
    <div id="notification" class="fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 z-50">
        <span id="notification-text"></span>
    </div>
    
    <style>
        @keyframes bounce-once {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }
        .animate-bounce-once {
            animation: bounce-once 0.5s ease-in-out;
        }
        
        /* Penalty indicator styles */
        .penalty-indicator {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            color: white;
            transition: all 0.3s ease;
        }
        
        .penalty-goal {
            background-color: #10b981;
        }
        
        .penalty-miss {
            background-color: #ef4444;
        }
        
        /* Bottom bar animation */
        .show-penalty-bar {
            transform: translateY(0) !important;
        }
        
        /* Team color classes for penalty sequence */
        .team-a-penalty {
            background-color: #dbeafe;
            color: #1e3a8a;
        }
        
        .team-b-penalty {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
    
    <!-- Include Pusher Script -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    
    <script>
        // Global variables
        let matchId = {{ $match->id }};
        let timerInterval = null;
        let currentMinutes = 0;
        let currentSeconds = 0;
        let isTimerRunning = false;
        let pusher = null;
        let channel = null;
        
        // Tie-breaker variables
        let tieBreakerData = {
            team_a: { goals: 0, attempts: [], currentShooter: 1 },
            team_b: { goals: 0, attempts: [], currentShooter: 1 },
            isActive: false,
            round: 1
        };

        // Initialize timer from saved match time
        window.onload = function() {
            currentMinutes = {{ floor($match->match_time) }};
            currentSeconds = {{ ($match->match_time * 60) % 60 }};
            updateTimerDisplay();
            
            // Initialize Pusher
            initializePusher();
            
            // Auto-fill current time in event form
            updateEventMinuteField();
            
            // Restore overlay settings
            const savedShowEvents = localStorage.getItem(`match_${matchId}_show_events`);
            if (savedShowEvents !== null) {
                document.getElementById('show_events_ticker').checked = savedShowEvents === 'true';
                updateOverlaySettings();
            }
            
            // Check if match is already finished and has a winner
            @if($match->status === 'finished')
                checkMatchResult();
            @endif
            
            // Check penalty shootout enabled status
            const penaltyEnabled = document.getElementById('penalty_shootout_enabled').checked;
            if (penaltyEnabled) {
                document.getElementById('quick_penalty_access').style.display = 'inline-block';
            }
            
            // Restore tie-breaker data if exists
            @if($match->is_tie_breaker && $match->tie_breaker_data)
                tieBreakerData = @json($match->tie_breaker_data);
                if (tieBreakerData.isActive) {
                    document.getElementById('tie_breaker_panel').style.display = 'block';
                    
                    // Restore penalty sequence
                    const sequenceContainer = document.getElementById('penalty_sequence');
                    sequenceContainer.innerHTML = '';
                    
                    // Recreate sequence from both teams' attempts
                    let allPenalties = [];
                    tieBreakerData.team_a.attempts.forEach((scored, index) => {
                        allPenalties.push({ team: 'team_a', scored, index });
                    });
                    tieBreakerData.team_b.attempts.forEach((scored, index) => {
                        allPenalties.push({ team: 'team_b', scored, index });
                    });
                    
                    // Sort by index to maintain order
                    allPenalties.sort((a, b) => {
                        if (a.index < 5 && b.index < 5) {
                            return a.index === b.index ? (a.team === 'team_a' ? -1 : 1) : a.index - b.index;
                        }
                        return 0;
                    });
                    
                    // Display sequence
                    allPenalties.forEach(penalty => {
                        updatePenaltySequence(penalty.team, penalty.scored);
                    });
                    
                    updatePenaltyDisplay('team_a');
                    updatePenaltyDisplay('team_b');
                    checkTieBreakerStatus();
                }
            @endif
        }

        // Auto-update event minute field flag
        let autoUpdateEventTime = true;
        
        // Timer Functions
        function updateTimerDisplay() {
            const display = document.getElementById('match_time_display');
            const minutes = currentMinutes.toString().padStart(2, '0');
            const seconds = currentSeconds.toString().padStart(2, '0');
            display.textContent = `${minutes}:${seconds}`;
            
            // Update manual inputs
            document.getElementById('manual_minutes').value = currentMinutes;
            document.getElementById('manual_seconds').value = currentSeconds;
            
            // Auto-update event minute field if enabled
            if (autoUpdateEventTime) {
                updateEventMinuteField();
            }
        }
        
        // Update event minute field
        function updateEventMinuteField() {
            const eventMinuteInput = document.getElementById('event_minute');
            if (eventMinuteInput && !eventMinuteInput.matches(':focus')) {
                eventMinuteInput.value = currentMinutes;
            }
        }
        
        // Set current time in event manually
        function setCurrentTimeInEvent() {
            document.getElementById('event_minute').value = currentMinutes;
            // Flash effect to indicate update
            const input = document.getElementById('event_minute');
            input.style.backgroundColor = '#3b82f6';
            input.style.color = 'white';
            setTimeout(() => {
                input.style.backgroundColor = '';
                input.style.color = '';
            }, 300);
        }

        function startTimer() {
            if (!isTimerRunning) {
                isTimerRunning = true;
                document.getElementById('start_btn').style.display = 'none';
                document.getElementById('pause_btn').style.display = 'inline-block';
                
                timerInterval = setInterval(() => {
                    currentSeconds++;
                    if (currentSeconds >= 60) {
                        currentSeconds = 0;
                        currentMinutes++;
                    }
                    updateTimerDisplay();
                    
                    // Auto-save every 10 seconds
                    if (currentSeconds % 10 === 0) {
                        saveTime();
                    }
                }, 1000);
            }
        }

        function pauseTimer() {
            if (isTimerRunning) {
                isTimerRunning = false;
                clearInterval(timerInterval);
                document.getElementById('start_btn').style.display = 'inline-block';
                document.getElementById('pause_btn').style.display = 'none';
                
                // Save time when paused
                saveTime();
            }
        }

        function resetTimer() {
            pauseTimer();
            currentMinutes = 0;
            currentSeconds = 0;
            updateTimerDisplay();
            saveTime();
        }

        function setQuickTime(minutes) {
            currentMinutes = minutes;
            currentSeconds = 0;
            updateTimerDisplay();
            saveTime();
        }

        function addExtraTime(extraMinutes) {
            currentMinutes = 45;
            currentSeconds = 0;
            // Add extra time visual indicator
            const display = document.getElementById('match_time_display');
            display.textContent = `45+${extraMinutes}`;
            
            // Actually set to 45 + extra
            setTimeout(() => {
                currentMinutes = 45 + extraMinutes;
                updateTimerDisplay();
                saveTime();
            }, 1000);
        }

        function setManualTime() {
            const minutes = parseInt(document.getElementById('manual_minutes').value) || 0;
            const seconds = parseInt(document.getElementById('manual_seconds').value) || 0;
            
            if (seconds > 59) {
                alert('সেকেন্ড 59 এর বেশি হতে পারবে না');
                return;
            }
            
            currentMinutes = minutes;
            currentSeconds = seconds;
            updateTimerDisplay();
            saveTime();
        }

        function saveTime() {
            const totalTime = currentMinutes + (currentSeconds / 60);
            
            fetch(`/matches/${matchId}/update-time`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    match_time: totalTime
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Time saved');
                }
            })
            .catch(error => {
                console.error('Error saving time:', error);
            });
        }

        // Score Functions
        function changeScore(team, delta) {
            const scoreElement = document.getElementById(team + '_score');
            let newScore = parseInt(scoreElement.textContent) + delta;
            if (newScore < 0) newScore = 0;
            
            scoreElement.textContent = newScore;
            
            // Smooth animation
            scoreElement.style.transition = 'all 0.3s ease';
            scoreElement.style.transform = 'scale(1.2)';
            setTimeout(() => {
                scoreElement.style.transform = 'scale(1)';
            }, 300);
            
            // Update score immediately
            updateScore();
        }
        
        function updateScore() {
            const scores = {
                team_a_score: parseInt(document.getElementById('team_a_score').textContent),
                team_b_score: parseInt(document.getElementById('team_b_score').textContent)
            };
            
            console.log('Updating score:', scores);
            
            fetch(`/matches/${matchId}/update-score`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(scores)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Score updated:', data);
                    showNotification('Score updated successfully', 'success');
                } else {
                    console.error('Error response:', data);
                    showNotification('Error updating score', 'error');
                }
            })
            .catch(error => {
                console.error('Error updating score:', error);
                showNotification('Error updating score', 'error');
            });
        }

        // Status Functions
        function updateStatus(status) {
            fetch(`/matches/${matchId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('current_status').textContent = status.charAt(0).toUpperCase() + status.slice(1);
                    
                    // Update button styles
                    document.querySelectorAll('.card button').forEach(btn => {
                        if (btn.textContent.includes('অপেক্ষমান') || btn.textContent.includes('লাইভ') || btn.textContent.includes('সমাপ্ত')) {
                            btn.classList.remove('bg-gray-600', 'bg-green-600', 'bg-red-600', 'text-white');
                            btn.classList.add('bg-gray-200');
                        }
                    });
                    
                    // Highlight current status button
                    event.target.classList.remove('bg-gray-200');
                    if (status === 'pending') event.target.classList.add('bg-gray-600', 'text-white');
                    if (status === 'live') event.target.classList.add('bg-green-600', 'text-white');
                    if (status === 'finished') event.target.classList.add('bg-red-600', 'text-white');
                    
                    // Auto-start timer if status is live
                    if (status === 'live' && !isTimerRunning) {
                        startTimer();
                    } else if (status !== 'live' && isTimerRunning) {
                        pauseTimer();
                    }
                    
                    // Show winner selection if finished
                    if (status === 'finished') {
                        document.getElementById('winner_selection').style.display = 'block';
                        // Auto-check result
                        setTimeout(checkMatchResult, 100);
                    } else {
                        document.getElementById('winner_selection').style.display = 'none';
                        document.getElementById('tie_breaker_panel').style.display = 'none';
                    }
                }
            });
        }

        // Event Functions
        function addEvent(event, customData = null) {
            if (event && event.preventDefault) {
                event.preventDefault();
            }
            
            let data;
            if (customData) {
                data = customData;
            } else {
                const formData = new FormData(event.target);
                data = Object.fromEntries(formData);
            }
            
            // Set current time if not specified
            if (!data.minute) {
                data.minute = currentMinutes;
            }
            
            fetch(`/matches/${matchId}/add-event`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(responseData => {
                if (responseData.success) {
                    console.log('Event added');
                    if (event && event.target && event.target.reset) {
                        event.target.reset();
                    }
                    showNotification('Event added successfully', 'success');
                    // Re-enable auto-update and update event minute field with current time
                    autoUpdateEventTime = true;
                    updateEventMinuteField();
                    // Add event to list without reload
                    addEventToList(responseData.event);
                }
            });
        }
        
        // Add event to list dynamically
        function addEventToList(event) {
            const eventsList = document.getElementById('events_list');
            const noEventsText = eventsList.querySelector('.text-gray-500');
            
            if (noEventsText) {
                noEventsText.remove();
            }
            
            const eventDiv = document.createElement('div');
            eventDiv.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg opacity-0 transition-opacity duration-500';
            eventDiv.innerHTML = `
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="font-medium text-sm">${event.minute}'</span>
                        <span class="text-sm text-gray-600">${event.event_type}</span>
                    </div>
                    <p class="text-sm text-gray-800 bangla-text">${event.player || 'Unknown'}</p>
                    <p class="text-xs text-gray-500 bangla-text">${event.team === 'team_a' ? '{{ $match->team_a }}' : '{{ $match->team_b }}'}</p>
                </div>
            `;
            
            eventsList.insertBefore(eventDiv, eventsList.firstChild);
            
            // Animate in
            setTimeout(() => {
                eventDiv.classList.remove('opacity-0');
            }, 10);
        }

        // Overlay Functions
        function generateOverlayLink() {
            console.log('Generating overlay link...');
            
            fetch(`/matches/${matchId}/generate-overlay-link`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.overlay_url) {
                    showNotification('ওভারলে লিংক তৈরি হয়েছে!', 'success');
                    // Update the URL field if it exists
                    const urlInput = document.getElementById('overlay_url');
                    if (urlInput) {
                        urlInput.value = data.overlay_url;
                    }
                    // Reload after a short delay
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification('লিংক তৈরি করতে সমস্যা হয়েছে', 'error');
                }
            })
            .catch(error => {
                console.error('Error generating overlay link:', error);
                showNotification('লিংক তৈরি করতে ত্রুটি: ' + error.message, 'error');
            });
        }

        function copyOverlayUrl() {
            const urlInput = document.getElementById('overlay_url');
            urlInput.select();
            navigator.clipboard.writeText(urlInput.value).then(() => {
                // Show success message
                const button = event.target;
                const originalText = button.innerHTML;
                button.innerHTML = '<span class="bangla-text">✓ কপি হয়েছে</span>';
                button.classList.add('bg-green-500');
                
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.classList.remove('bg-green-500');
                }, 2000);
            });
        }
        
        function updateOverlaySettings() {
            const showEventsTicker = document.getElementById('show_events_ticker').checked;
            
            // Save settings to localStorage
            localStorage.setItem(`match_${matchId}_show_events`, showEventsTicker);
            
            // Update overlay URL with parameter
            const overlayUrl = document.getElementById('overlay_url');
            if (overlayUrl) {
                const baseUrl = overlayUrl.value.split('?')[0];
                overlayUrl.value = showEventsTicker ? `${baseUrl}?events=1` : baseUrl;
            }
        }

        // Pusher Functions
        function initializePusher() {
            try {
                console.log('Initializing Pusher...');
                pusher = new Pusher('8ccf5d4c4bf78fcec3c9', {
                    cluster: 'ap2',
                    encrypted: true,
                    forceTLS: true
                });

                channel = pusher.subscribe('match.' + matchId);
                console.log('Subscribed to channel:', 'match.' + matchId);

                channel.bind('match-updated', function(data) {
                    console.log('Match updated:', data);
                    handleRealtimeUpdate(data);
                });
                
                channel.bind('event-added', function(data) {
                    console.log('Event added:', data);
                    if (data.event) {
                        addEventToList(data.event);
                    }
                });

                pusher.connection.bind('connected', function() {
                    console.log('Pusher connected successfully');
                    updatePusherStatus('connected');
                });

                pusher.connection.bind('disconnected', function() {
                    console.log('Pusher disconnected');
                    updatePusherStatus('disconnected');
                });
                
                pusher.connection.bind('error', function(err) {
                    console.error('Pusher connection error:', err);
                    updatePusherStatus('error');
                });

            } catch (error) {
                console.error('Pusher initialization error:', error);
                updatePusherStatus('error');
            }
        }

        function updatePusherStatus(status) {
            const statusElement = document.getElementById('pusher_status');
            const statusText = document.getElementById('pusher_status_text');
            
            switch(status) {
                case 'connected':
                    statusElement.classList.remove('bg-red-500', 'bg-yellow-500');
                    statusElement.classList.add('bg-green-500');
                    statusText.textContent = 'Connected';
                    break;
                case 'disconnected':
                    statusElement.classList.remove('bg-green-500', 'bg-yellow-500');
                    statusElement.classList.add('bg-red-500');
                    statusText.textContent = 'Disconnected';
                    break;
                case 'error':
                    statusElement.classList.remove('bg-green-500', 'bg-red-500');
                    statusElement.classList.add('bg-yellow-500');
                    statusText.textContent = 'Error';
                    break;
            }
        }

        function handleRealtimeUpdate(data) {
            if (data.match) {
                // Flash effect
                document.body.style.backgroundColor = '#e0f2fe';
                setTimeout(() => {
                    document.body.style.backgroundColor = '';
                }, 300);
            }
        }

        // Load players on page load
        loadPlayers();
        
        // Player Management Functions
        function loadPlayers() {
            fetch(`/matches/${matchId}/players`)
                .then(response => response.json())
                .then(data => {
                    displayPlayers('home', data.home_players);
                    displayPlayers('away', data.away_players);
                })
                .catch(error => {
                    console.error('Error loading players:', error);
                });
        }
        
        function displayPlayers(team, players) {
            const listElement = document.getElementById(`${team}_players_list`);
            
            if (players.length === 0) {
                listElement.innerHTML = '<p class="text-xs text-gray-500 text-center py-2">কোনো প্লেয়ার নেই</p>';
                return;
            }
            
            listElement.innerHTML = players.map(player => `
                <div class="flex items-center justify-between p-1 hover:bg-white rounded transition-colors group">
                    <div class="flex items-center space-x-2">
                        <span class="font-bold text-xs">${player.jersey_number || '--'}</span>
                        <span class="text-xs">${player.name}</span>
                        ${player.position ? `<span class="text-xs text-gray-500">(${player.position})</span>` : ''}
                    </div>
                    <button onclick="removePlayer(${player.id})" class="opacity-0 group-hover:opacity-100 text-red-500 hover:text-red-700 transition-opacity">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `).join('');
        }
        
        function addPlayer(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData);
            
            fetch(`/matches/${matchId}/players`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(responseData => {
                if (responseData.success) {
                    event.target.reset();
                    loadPlayers();
                    showNotification('প্লেয়ার যোগ করা হয়েছে', 'success');
                }
            })
            .catch(error => {
                console.error('Error adding player:', error);
                showNotification('প্লেয়ার যোগ করতে সমস্যা', 'error');
            });
        }
        
        function removePlayer(playerId) {
            if (!confirm('আপনি কি নিশ্চিত এই প্লেয়ার মুছে ফেলতে চান?')) {
                return;
            }
            
            fetch(`/matches/${matchId}/players/${playerId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadPlayers();
                    showNotification('প্লেয়ার মুছে ফেলা হয়েছে', 'success');
                }
            })
            .catch(error => {
                console.error('Error removing player:', error);
                showNotification('প্লেয়ার মুছতে সমস্যা', 'error');
            });
        }
        
        function togglePlayerList() {
            fetch(`/matches/${matchId}/toggle-player-list`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const btn = document.getElementById('player_list_toggle_btn');
                    if (data.show_player_list) {
                        btn.classList.remove('bg-gray-200');
                        btn.classList.add('bg-green-500', 'text-white');
                        btn.innerHTML = '<span class="bangla-text">🔴 লাইভ</span>';
                        showNotification('প্লেয়ার লিস্ট ওভারলেতে দেখানো হচ্ছে', 'success');
                    } else {
                        btn.classList.remove('bg-green-500', 'text-white');
                        btn.classList.add('bg-gray-200');
                        btn.innerHTML = '<span class="bangla-text">প্লেয়ার লিস্ট দেখান</span>';
                        showNotification('প্লেয়ার লিস্ট লুকানো হয়েছে', 'info');
                    }
                }
            })
            .catch(error => {
                console.error('Error toggling player list:', error);
                showNotification('প্লেয়ার লিস্ট টগল করতে সমস্যা', 'error');
            });
        }
        
        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Space bar to start/pause timer
            if (e.code === 'Space' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA' && e.target.tagName !== 'SELECT') {
                e.preventDefault();
                if (isTimerRunning) {
                    pauseTimer();
                } else {
                    startTimer();
                }
            }
            
            // R key to reset timer
            if (e.key === 'r' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA' && e.target.tagName !== 'SELECT') {
                e.preventDefault();
                resetTimer();
            }
        });
        
        // Notification System
        function showNotification(message, type = 'info') {
            const notification = document.getElementById('notification');
            const notificationText = document.getElementById('notification-text');
            
            notificationText.textContent = message;
            
            // Set color based on type
            notification.className = 'fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg transform transition-transform duration-300 z-50';
            
            switch(type) {
                case 'success':
                    notification.classList.add('bg-green-500', 'text-white');
                    break;
                case 'error':
                    notification.classList.add('bg-red-500', 'text-white');
                    break;
                case 'warning':
                    notification.classList.add('bg-yellow-500', 'text-white');
                    break;
                default:
                    notification.classList.add('bg-blue-500', 'text-white');
            }
            
            // Show notification
            notification.classList.remove('translate-x-full');
            
            // Hide after 3 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
            }, 3000);
        }
        
        // Smooth scrolling for events list
        const eventsList = document.getElementById('events_list');
        if (eventsList) {
            eventsList.style.scrollBehavior = 'smooth';
        }
        
        // Prevent accidental page refresh
        window.addEventListener('beforeunload', function (e) {
            if (isTimerRunning) {
                e.preventDefault();
                e.returnValue = 'Timer is running. Are you sure you want to leave?';
            }
        });
        
        // Event minute field listeners
        document.getElementById('event_minute').addEventListener('focus', function() {
            // Disable auto-update when user is typing
            autoUpdateEventTime = false;
        });
        
        document.getElementById('event_minute').addEventListener('input', function() {
            // Keep auto-update disabled while user is editing
            autoUpdateEventTime = false;
        });
        
        document.getElementById('event_minute').addEventListener('blur', function() {
            // If field is empty, re-enable auto-update
            if (this.value === '' || this.value == currentMinutes) {
                autoUpdateEventTime = true;
            }
        });
        
        // Toggle Penalty Shootout
        function togglePenaltyShootout() {
            const enabled = document.getElementById('penalty_shootout_enabled').checked;
            const quickAccessBtn = document.getElementById('quick_penalty_access');
            
            if (enabled) {
                // Show quick access button
                quickAccessBtn.style.display = 'inline-block';
                
                showNotification('পেনাল্টি মোড সক্রিয় - আপনি এখন পেনাল্টি যোগ করতে পারবেন', 'success');
            } else {
                // Hide quick access button and penalty panel
                quickAccessBtn.style.display = 'none';
                
                // Hide penalty panel if open
                document.getElementById('tie_breaker_panel').style.display = 'none';
                
                // Reset penalty data
                tieBreakerData.isActive = false;
                saveTieBreakerData();
                
                showNotification('পেনাল্টি মোড নিষ্ক্রিয়', 'info');
            }
            
            fetch(`/matches/${matchId}/update-settings`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    penalty_shootout_enabled: enabled
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Penalty shootout setting updated');
                }
            });
        }
        
        // Check match result and display appropriately
        function checkMatchResult() {
            const teamAScore = parseInt(document.getElementById('team_a_score').textContent);
            const teamBScore = parseInt(document.getElementById('team_b_score').textContent);
            const resultDisplay = document.getElementById('match_result_display');
            const resultText = document.getElementById('result_text');
            const scoreText = document.getElementById('score_text');
            const winnerButtons = document.getElementById('winner_buttons');
            
            if (teamAScore > teamBScore) {
                resultText.textContent = '{{ $match->team_a }} বিজয়ী!';
                resultText.className = 'text-2xl font-bold text-blue-600';
                scoreText.textContent = `${teamAScore} - ${teamBScore}`;
                resultDisplay.style.display = 'block';
                winnerButtons.style.display = 'none';
            } else if (teamBScore > teamAScore) {
                resultText.textContent = '{{ $match->team_b }} বিজয়ী!';
                resultText.className = 'text-2xl font-bold text-red-600';
                scoreText.textContent = `${teamAScore} - ${teamBScore}`;
                resultDisplay.style.display = 'block';
                winnerButtons.style.display = 'none';
            } else {
                // Match is drawn
                resultText.textContent = 'ম্যাচ ড্র!';
                resultText.className = 'text-2xl font-bold text-gray-600';
                scoreText.textContent = `${teamAScore} - ${teamBScore}`;
                resultDisplay.style.display = 'block';
                // Show tie-breaker option if penalty is enabled
                const penaltyEnabled = document.getElementById('penalty_shootout_enabled').checked;
                if (penaltyEnabled) {
                    document.getElementById('tie_check').style.display = 'block';
                }
                winnerButtons.style.display = 'none';
            }
        }
        
        // Tie-Breaker Functions
        function startTieBreaker() {
            tieBreakerData.isActive = true;
            document.getElementById('tie_breaker_panel').style.display = 'block';
            document.getElementById('tie_check').style.display = 'none';
            
            // Hide match result display
            document.getElementById('match_result_display').style.display = 'none';
            
            // Save tie-breaker status
            saveTieBreakerData();
            
            // Add event
            addEvent({
                preventDefault: () => {},
                target: {
                    reset: () => {}
                }
            }, {
                event_type: 'tie_breaker_start',
                team: 'both',
                player: 'Penalty Shootout Started',
                minute: currentMinutes
            });
            
            showNotification('টাই-ব্রেকার শুরু হয়েছে!', 'info');
        }
        
        function addPenalty(team, scored) {
            const teamData = tieBreakerData[team];
            teamData.attempts.push(scored);
            if (scored) {
                teamData.goals++;
            }
            
            // Update display
            updatePenaltyDisplay(team);
            
            // Update penalty sequence
            updatePenaltySequence(team, scored);
            
            // Check if tie-breaker should end
            checkTieBreakerStatus();
            
            // Save data immediately with penalty broadcast
            saveTieBreakerData();
            
            // Add penalty-specific event (not as a regular event)
            const teamName = team === 'team_a' ? '{{ $match->team_a }}' : '{{ $match->team_b }}';
            
            // Don't add as regular event - penalties have their own notification system
            // This prevents the lower third from appearing
            console.log(`Penalty ${teamData.attempts.length}: ${scored ? 'GOAL' : 'MISS'} by ${teamName}`);
        }
        
        function updatePenaltySequence(team, scored) {
            const sequenceContainer = document.getElementById('penalty_sequence');
            const teamName = team === 'team_a' ? '{{ $match->team_a }}' : '{{ $match->team_b }}';
            
            const sequenceItem = document.createElement('div');
            sequenceItem.className = `flex items-center space-x-1 px-2 py-1 rounded text-xs ${team === 'team_a' ? 'team-a-penalty' : 'team-b-penalty'}`;
            sequenceItem.innerHTML = `
                <span class="font-medium">${teamName.substring(0, 3)}</span>
                <span class="${scored ? 'text-green-600' : 'text-red-600'} font-bold">
                    ${scored ? '✓' : '×'}
                </span>
            `;
            
            sequenceContainer.appendChild(sequenceItem);
            
            // Scroll to show latest
            sequenceContainer.scrollLeft = sequenceContainer.scrollWidth;
        }
        
        function updatePenaltyDisplay(team) {
            const teamData = tieBreakerData[team];
            const goalsElement = document.getElementById(`${team}_penalties`);
            const attemptsElement = document.getElementById(`${team}_attempts`);
            const shotsContainer = document.getElementById(`${team}_penalty_shots`);
            
            goalsElement.textContent = teamData.goals;
            attemptsElement.textContent = teamData.attempts.length;
            
            // Update visual indicators
            shotsContainer.innerHTML = '';
            teamData.attempts.forEach((scored, index) => {
                const indicator = document.createElement('div');
                indicator.className = `w-8 h-8 rounded-full flex items-center justify-center text-white font-bold transition-all transform ${
                    scored ? 'bg-green-500' : 'bg-red-500'
                } animate-bounce-once`;
                indicator.innerHTML = scored ? '✓' : '×';
                indicator.style.animationDelay = `${index * 0.1}s`;
                shotsContainer.appendChild(indicator);
            });
            
            // Update big score display
            document.getElementById(`${team}_score_big`).textContent = teamData.goals;
            
            // Update success rate
            const successRate = teamData.attempts.length > 0 
                ? Math.round((teamData.goals / teamData.attempts.length) * 100) 
                : 0;
            document.getElementById(`${team}_success_rate`).textContent = `${successRate}%`;
            
            // Update total penalties
            const totalPenalties = tieBreakerData.team_a.attempts.length + tieBreakerData.team_b.attempts.length;
            document.getElementById('total_penalties').textContent = totalPenalties;
            
            // Update round info
            updatePenaltyRoundInfo();
        }
        
        function updatePenaltyRoundInfo() {
            const teamAAttempts = tieBreakerData.team_a.attempts.length;
            const teamBAttempts = tieBreakerData.team_b.attempts.length;
            const maxAttempts = Math.max(teamAAttempts, teamBAttempts);
            const roundInfo = document.getElementById('penalty_round_info');
            
            if (maxAttempts <= 5) {
                roundInfo.textContent = `রাউন্ড ১ (${maxAttempts}/৫ পেনাল্টি)`;
            } else {
                const suddenDeathRound = maxAttempts - 4;
                roundInfo.textContent = `সাডেন ডেথ - রাউন্ড ${suddenDeathRound}`;
            }
        }
        
        // Bottom Bar Functions
        function showPenaltyBar() {
            const bar = document.getElementById('penalty_bottom_bar');
            const practiceText = document.getElementById('practice_mode_text');
            
            // Check if in practice mode (not in actual tie-breaker)
            if (!tieBreakerData.isActive) {
                practiceText.style.display = 'block';
            } else {
                practiceText.style.display = 'none';
            }
            
            setTimeout(() => {
                bar.classList.add('show-penalty-bar');
            }, 100);
        }
        
        function hidePenaltyBar() {
            const bar = document.getElementById('penalty_bottom_bar');
            bar.classList.remove('show-penalty-bar');
        }
        
        function updateBottomBar(team) {
            const teamData = tieBreakerData[team];
            
            // Update scores
            document.getElementById(`bottom_${team}_score`).textContent = teamData.goals;
            document.getElementById(`bottom_${team}_attempts`).textContent = teamData.attempts.length;
            
            // Update indicators
            const indicatorsContainer = document.getElementById(`bottom_${team}_indicators`);
            indicatorsContainer.innerHTML = '';
            
            teamData.attempts.forEach((scored, index) => {
                const indicator = document.createElement('div');
                indicator.className = `penalty-indicator ${scored ? 'penalty-goal' : 'penalty-miss'}`;
                indicator.innerHTML = scored ? '✓' : '×';
                indicatorsContainer.appendChild(indicator);
            });
            
            // Update status
            const teamAAttempts = tieBreakerData.team_a.attempts.length;
            const teamBAttempts = tieBreakerData.team_b.attempts.length;
            const statusElement = document.getElementById('bottom_status');
            
            if (Math.max(teamAAttempts, teamBAttempts) < 5) {
                statusElement.textContent = `Kick ${Math.max(teamAAttempts, teamBAttempts) + 1}/5`;
            } else {
                statusElement.textContent = 'Sudden Death';
            }
        }
        
        // Toggle Tie-Breaker Panel
        function toggleTieBreakerPanel() {
            const panel = document.getElementById('tie_breaker_panel');
            const penaltyEnabled = document.getElementById('penalty_shootout_enabled').checked;
            
            // Check if penalty mode is enabled first
            if (!penaltyEnabled) {
                showNotification('প্রথমে পেনাল্টি শুটআউট সক্রিয় করুন', 'warning');
                return;
            }
            
            if (panel.style.display === 'none' || panel.style.display === '') {
                // Initialize tie-breaker if not active
                if (!tieBreakerData.isActive) {
                    tieBreakerData.isActive = true;
                    tieBreakerData.team_a = { goals: 0, attempts: [], currentShooter: 1 };
                    tieBreakerData.team_b = { goals: 0, attempts: [], currentShooter: 1 };
                    tieBreakerData.round = 1;
                    saveTieBreakerData();
                }
                panel.style.display = 'block';
                showNotification('পেনাল্টি কন্ট্রোলার খোলা হয়েছে', 'info');
            } else {
                panel.style.display = 'none';
                showNotification('পেনাল্টি কন্ট্রোলার বন্ধ করা হয়েছে', 'info');
            }
        }
        
        function quickPenalty(team, scored) {
            // Check if penalty mode is enabled
            const penaltyEnabled = document.getElementById('penalty_shootout_enabled').checked;
            
            if (!penaltyEnabled) {
                showNotification('প্রথমে পেনাল্টি মোড অন করুন', 'warning');
                return;
            }
            
            // If tie-breaker not active, initialize and show panel
            if (!tieBreakerData.isActive) {
                // Initialize tie-breaker data
                tieBreakerData.isActive = true;
                tieBreakerData.team_a = { goals: 0, attempts: [], currentShooter: 1 };
                tieBreakerData.team_b = { goals: 0, attempts: [], currentShooter: 1 };
                tieBreakerData.round = 1;
                
                // Show panel
                document.getElementById('tie_breaker_panel').style.display = 'block';
                
                saveTieBreakerData();
            }
            
            // Now add the penalty
            addPenalty(team, scored);
        }
        
        function checkTieBreakerStatus() {
            const teamAData = tieBreakerData.team_a;
            const teamBData = tieBreakerData.team_b;
            const statusElement = document.getElementById('tie_breaker_status');
            
            // Both teams must have same number of attempts (except in sudden death)
            const minAttempts = Math.min(teamAData.attempts.length, teamBData.attempts.length);
            const maxAttempts = Math.max(teamAData.attempts.length, teamBData.attempts.length);
            
            if (maxAttempts < 5) {
                statusElement.textContent = `প্রথম ৫টি কিক চলছে... (${maxAttempts}/5)`;
            } else {
                // Check if there's a winner after 5 kicks each
                if (minAttempts >= 5) {
                    const goalDiff = Math.abs(teamAData.goals - teamBData.goals);
                    const remainingKicks = 5 - minAttempts;
                    
                    if (minAttempts === maxAttempts && teamAData.goals !== teamBData.goals) {
                        // We have a winner
                        const winner = teamAData.goals > teamBData.goals ? 'team_a' : 'team_b';
                        statusElement.textContent = 'টাই-ব্রেকার শেষ! বিজয়ী ঘোষণা করুন।';
                        // Auto-declare winner
                        setTimeout(() => declareTieBreakerWinner(winner), 1000);
                    } else if (goalDiff > remainingKicks && minAttempts === maxAttempts) {
                        // Mathematical winner
                        const winner = teamAData.goals > teamBData.goals ? 'team_a' : 'team_b';
                        statusElement.textContent = 'টাই-ব্রেকার শেষ! বিজয়ী ঘোষণা করুন।';
                        setTimeout(() => declareTieBreakerWinner(winner), 1000);
                    } else {
                        statusElement.textContent = `সাডেন ডেথ চলছে... (${teamAData.goals}-${teamBData.goals})`;
                    }
                }
            }
        }
        
        function declareTieBreakerWinner(winner) {
            const teamAGoals = tieBreakerData.team_a.goals;
            const teamBGoals = tieBreakerData.team_b.goals;
            const penaltyScore = `(${teamAGoals}-${teamBGoals} on penalties)`;
            
            let winnerTeam = winner === 'team_a' ? '{{ $match->team_a }}' : '{{ $match->team_b }}';
            let finalScore = `${document.getElementById('team_a_score').textContent} - ${document.getElementById('team_b_score').textContent} ${penaltyScore}`;
            
            // Send winner announcement
            fetch(`/matches/${matchId}/add-event`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    event_type: 'winner_announcement',
                    team: winner,
                    player: `${winnerTeam} (Penalties: ${teamAGoals}-${teamBGoals})`,
                    minute: currentMinutes,
                    description: `Final Score: ${finalScore}`
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(`টাই-ব্রেকার বিজয়ী: ${winnerTeam}!`, 'success');
                    // Disable all penalty buttons
                    document.querySelectorAll('#tie_breaker_panel button').forEach(btn => {
                        btn.disabled = true;
                        btn.classList.add('opacity-50', 'cursor-not-allowed');
                    });
                }
            });
        }
        
        function endTieBreaker() {
            if (confirm('আপনি কি নিশ্চিত যে টাই-ব্রেকার শেষ করতে চান?')) {
                tieBreakerData.isActive = false;
                document.getElementById('tie_breaker_panel').style.display = 'none';
                saveTieBreakerData();
            }
        }
        
        function saveTieBreakerData() {
            // First update tie breaker status
            fetch(`/matches/${matchId}/update-tiebreaker`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    is_tie_breaker: tieBreakerData.isActive,
                    tie_breaker_data: tieBreakerData
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Tie-breaker data saved');
                    
                    // Now update penalty data for real-time broadcast
                    if (tieBreakerData.isActive) {
                        console.log('Sending penalty update:', tieBreakerData);
                        return fetch(`/matches/${matchId}/update-penalty`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                tie_breaker_data: tieBreakerData
                            })
                        });
                    }
                }
            })
            .then(response => {
                if (response) {
                    return response.json();
                }
            })
            .then(data => {
                if (data && data.success) {
                    console.log('Penalty data broadcasted:', data);
                }
            })
            .catch(error => {
                console.error('Error saving/broadcasting data:', error);
            });
        }
        
        // Winner Declaration Function
        function declareWinner(winner) {
            let winnerTeam = '';
            let finalScore = `${document.getElementById('team_a_score').textContent} - ${document.getElementById('team_b_score').textContent}`;
            
            if (winner === 'team_a') {
                winnerTeam = '{{ $match->team_a }}';
            } else if (winner === 'team_b') {
                winnerTeam = '{{ $match->team_b }}';
            } else {
                winnerTeam = 'DRAW';
                finalScore += ' (Draw)';
                // Show tie-breaker option if it's a draw and penalty is enabled
                const teamAScore = parseInt(document.getElementById('team_a_score').textContent);
                const teamBScore = parseInt(document.getElementById('team_b_score').textContent);
                const penaltyEnabled = document.getElementById('penalty_shootout_enabled').checked;
                
                if (teamAScore === teamBScore && penaltyEnabled) {
                    document.getElementById('tie_check').style.display = 'block';
                    document.getElementById('match_result_display').style.display = 'block';
                    document.getElementById('result_text').textContent = 'ম্যাচ ড্র!';
                    document.getElementById('result_text').className = 'text-2xl font-bold text-gray-600';
                    document.getElementById('score_text').textContent = finalScore;
                    document.getElementById('winner_buttons').style.display = 'none';
                    return; // Don't send winner announcement yet
                }
            }
            
            // Send winner announcement event
            fetch(`/matches/${matchId}/add-event`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    event_type: 'winner_announcement',
                    team: winner,
                    player: winnerTeam,
                    minute: currentMinutes,
                    description: `Final Score: ${finalScore}`
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(`Winner declared: ${winnerTeam}`, 'success');
                    // Disable winner buttons
                    document.querySelectorAll('#winner_selection button').forEach(btn => {
                        btn.disabled = true;
                        btn.classList.add('opacity-50', 'cursor-not-allowed');
                    });
                }
            })
            .catch(error => {
                console.error('Error declaring winner:', error);
                showNotification('Error declaring winner', 'error');
            });
        }
    </script>
</x-app-layout>
