{{-- resources/views/matches/control.blade.php --}}
<x-app-layout>
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
                                <span class="text-lg font-mono bg-gray-100 px-3 py-1 rounded" id="match_time">{{ sprintf('%02d:%02d', floor($match->match_time), ($match->match_time * 60) % 60) }}</span>
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

                <!-- Time Control -->
                <div class="card mb-6">
                    <h2 class="text-xl font-semibold mb-4 bangla-text">সময় কন্ট্রোল</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 bangla-text">মিনিট</label>
                            <input type="number" id="time_minutes" value="{{ floor($match->match_time) }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" min="0" max="120">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 bangla-text">সেকেন্ড</label>
                            <input type="number" id="time_seconds" value="{{ ($match->match_time * 60) % 60 }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" min="0" max="59">
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 mt-4">
                        <button onclick="updateTime()" class="btn-primary">
                            <span class="bangla-text">সময় আপডেট</span>
                        </button>
                        <button onclick="toggleTimer()" class="btn-success" id="timer_btn">
                            <span class="bangla-text">টাইমার শুরু</span>
                        </button>
                        <button onclick="resetTime()" class="btn-secondary">
                            <span class="bangla-text">রিসেট</span>
                        </button>
                    </div>
                </div>

                <!-- Match Status -->
                <div class="card mb-6">
                    <h2 class="text-xl font-semibold mb-4 bangla-text">ম্যাচের অবস্থা</h2>
                    <div class="flex space-x-4">
                        <button onclick="updateStatus('pending')" 
                                class="btn-secondary {{ $match->status === 'pending' ? 'opacity-50' : '' }}">
                            <span class="bangla-text">অপেক্ষমান</span>
                        </button>
                        <button onclick="updateStatus('live')" 
                                class="btn-success {{ $match->status === 'live' ? 'opacity-50' : '' }}">
                            <span class="bangla-text">লাইভ</span>
                        </button>
                        <button onclick="updateStatus('finished')" 
                                class="btn-danger {{ $match->status === 'finished' ? 'opacity-50' : '' }}">
                            <span class="bangla-text">সমাপ্ত</span>
                        </button>
                    </div>
                    <p class="mt-2 text-sm text-gray-600 bangla-text">
                        বর্তমান অবস্থা: <span class="font-semibold">{{ ucfirst($match->status) }}</span>
                    </p>
                </div>

                <!-- Add Event -->
                <div class="card">
                    <h2 class="text-xl font-semibold mb-4 bangla-text">ইভেন্ট যোগ করুন</h2>
                    <form id="event_form" onsubmit="addEvent(event)">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 bangla-text">ইভেন্ট টাইপ</label>
                                <select name="event_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="goal">গোল</option>
                                    <option value="yellow_card">হলুদ কার্ড</option>
                                    <option value="red_card">লাল কার্ড</option>
                                    <option value="substitution">খেলোয়াড় পরিবর্তন</option>
                                    <option value="penalty">পেনাল্টি</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 bangla-text">টিম</label>
                                <select name="team" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="team_a">{{ $match->team_a }}</option>
                                    <option value="team_b">{{ $match->team_b }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 bangla-text">খেলোয়াড়</label>
                                <input type="text" name="player" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="খেলোয়াড়ের নাম">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 bangla-text">মিনিট</label>
                                <input type="number" name="minute" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" min="0" max="120">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 bangla-text">বিবরণ</label>
                            <textarea name="description" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="ইভেন্টের বিবরণ"></textarea>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn-primary">
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
                                   class="flex-1 border-gray-300 rounded-l-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" readonly>
                            <button onclick="copyOverlayUrl()" class="bg-blue-500 text-white px-4 py-2 rounded-r-md hover:bg-blue-600 transition-colors">
                                <span class="bangla-text">কপি</span>
                            </button>
                        </div>
                    </div>
                    @endif
                    <button onclick="generateOverlayLink()" class="btn-primary w-full">
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
            </div>
        </div>
    </div>

    <script>
        let matchId = {{ $match->id }};
        let timerInterval;
        let isTimerRunning = false;
        let currentMinutes = {{ floor($match->match_time) }};
        let currentSeconds = {{ ($match->match_time * 60) % 60 }};

        function changeScore(team, delta) {
            const scoreElement = document.getElementById(team + '_score');
            let newScore = parseInt(scoreElement.textContent) + delta;
            if (newScore < 0) newScore = 0;
            
            scoreElement.textContent = newScore;
            
            // Update via API
            const scores = {
                team_a_score: parseInt(document.getElementById('team_a_score').textContent),
                team_b_score: parseInt(document.getElementById('team_b_score').textContent)
            };
            
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
                    console.log('Score updated and broadcasted');
                    // Force update overlay immediately
                    window.postMessage({type: 'SCORE_UPDATED', scores: scores}, '*');
                }
            })
            .catch(error => {
                console.error('Error updating score:', error);
                // Revert score on error
                location.reload();
            });
        }

        function updateTime() {
            const minutes = parseInt(document.getElementById('time_minutes').value) || 0;
            const seconds = parseInt(document.getElementById('time_seconds').value) || 0;
            
            if (seconds > 59) {
                document.getElementById('time_seconds').value = 59;
                return;
            }
            
            currentMinutes = minutes;
            currentSeconds = seconds;
            
            const totalTime = minutes + (seconds / 60);
            
            document.getElementById('match_time').textContent = 
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            fetch(`/matches/${matchId}/update-time`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    match_time: totalTime,
                    minutes: minutes,
                    seconds: seconds 
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Time updated and broadcasted');
                    // Save timer state
                    localStorage.setItem(`match_${matchId}_timer`, JSON.stringify({
                        minutes: minutes,
                        seconds: seconds,
                        timestamp: Date.now()
                    }));
                }
            });
        }

        function toggleTimer() {
            const btn = document.getElementById('timer_btn');
            
            if (isTimerRunning) {
                clearInterval(timerInterval);
                btn.innerHTML = '<span class="bangla-text">টাইমার শুরু</span>';
                btn.className = 'btn-success';
                isTimerRunning = false;
                localStorage.setItem(`match_${matchId}_timer_running`, 'false');
            } else {
                timerInterval = setInterval(() => {
                    currentSeconds++;
                    if (currentSeconds >= 60) {
                        currentSeconds = 0;
                        currentMinutes++;
                    }
                    
                    document.getElementById('time_minutes').value = currentMinutes;
                    document.getElementById('time_seconds').value = currentSeconds;
                    updateTime();
                }, 1000);
                
                btn.innerHTML = '<span class="bangla-text">টাইমার স্টপ</span>';
                btn.className = 'btn-danger';
                isTimerRunning = true;
                localStorage.setItem(`match_${matchId}_timer_running`, 'true');
            }
        }

        function resetTime() {
            clearInterval(timerInterval);
            currentMinutes = 0;
            currentSeconds = 0;
            document.getElementById('time_minutes').value = 0;
            document.getElementById('time_seconds').value = 0;
            updateTime();
            
            const btn = document.getElementById('timer_btn');
            btn.innerHTML = '<span class="bangla-text">টাইমার শুরু</span>';
            btn.className = 'btn-success';
            isTimerRunning = false;
            
            // Clear timer state
            localStorage.removeItem(`match_${matchId}_timer`);
            localStorage.removeItem(`match_${matchId}_timer_running`);
        }

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
                    console.log('Status updated and broadcasted');
                    setTimeout(() => location.reload(), 500);
                }
            });
        }

        function addEvent(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData);
            
            fetch(`/matches/${matchId}/add-event`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Event added and broadcasted');
                    event.target.reset();
                    setTimeout(() => location.reload(), 500);
                }
            });
        }

        function generateOverlayLink() {
            fetch(`/matches/${matchId}/generate-overlay-link`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.overlay_url) {
                    document.getElementById('overlay_url').value = data.overlay_url;
                }
            });
        }

        function copyOverlayUrl() {
            const urlInput = document.getElementById('overlay_url');
            urlInput.select();
            navigator.clipboard.writeText(urlInput.value).then(() => {
                alert('লিংক কপি হয়েছে!');
            });
        }

        // Initialize timer state on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Restore timer state
            const savedTimer = localStorage.getItem(`match_${matchId}_timer`);
            const savedRunning = localStorage.getItem(`match_${matchId}_timer_running`);
            
            if (savedTimer) {
                const timerData = JSON.parse(savedTimer);
                const timeDiff = Math.floor((Date.now() - timerData.timestamp) / 1000);
                
                if (savedRunning === 'true') {
                    // Calculate elapsed time
                    let totalSeconds = (timerData.minutes * 60) + timerData.seconds + timeDiff;
                    currentMinutes = Math.floor(totalSeconds / 60);
                    currentSeconds = totalSeconds % 60;
                    
                    document.getElementById('time_minutes').value = currentMinutes;
                    document.getElementById('time_seconds').value = currentSeconds;
                    
                    // Resume timer
                    toggleTimer();
                } else {
                    currentMinutes = timerData.minutes;
                    currentSeconds = timerData.seconds;
                    document.getElementById('time_minutes').value = currentMinutes;
                    document.getElementById('time_seconds').value = currentSeconds;
                }
                
                updateTime();
            }
        });
    </script>
</x-app-layout>