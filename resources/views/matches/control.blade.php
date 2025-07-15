<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">ম্যাচ কন্ট্রোল প্যানেল</h1>
            <p class="text-gray-600">{{ $match->team_a }} vs {{ $match->team_b }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Match Control -->
            <div class="lg:col-span-2">
                <!-- Score Control -->
                <div class="card mb-6">
                    <h2 class="text-xl font-semibold mb-4">স্কোর কন্ট্রোল</h2>
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-center">
                            <h3 class="font-semibold text-lg">{{ $match->team_a }}</h3>
                            <div class="flex items-center justify-center space-x-2 mt-2">
                                <button onclick="changeScore('team_a', -1)" class="bg-red-500 text-white w-8 h-8 rounded">-</button>
                                <span id="team_a_score" class="text-3xl font-bold">{{ $match->team_a_score }}</span>
                                <button onclick="changeScore('team_a', 1)" class="bg-green-500 text-white w-8 h-8 rounded">+</button>
                            </div>
                        </div>

                        <div class="text-center">
                            <div class="text-2xl font-bold">VS</div>
                            <div class="mt-2">
                                <span class="text-lg" id="match_time">{{ $match->match_time }}'</span>
                            </div>
                        </div>

                        <div class="text-center">
                            <h3 class="font-semibold text-lg">{{ $match->team_b }}</h3>
                            <div class="flex items-center justify-center space-x-2 mt-2">
                                <button onclick="changeScore('team_b', -1)" class="bg-red-500 text-white w-8 h-8 rounded">-</button>
                                <span id="team_b_score" class="text-3xl font-bold">{{ $match->team_b_score }}</span>
                                <button onclick="changeScore('team_b', 1)" class="bg-green-500 text-white w-8 h-8 rounded">+</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Time Control -->
                <div class="card mb-6">
                    <h2 class="text-xl font-semibold mb-4">সময় কন্ট্রোল</h2>
                    <div class="flex items-center space-x-4">
                        <input type="number" id="time_input" value="{{ $match->match_time }}" 
                               class="border rounded px-3 py-2 w-20">
                        <button onclick="updateTime()" class="btn-primary">আপডেট</button>
                        <button onclick="startStopwatch()" class="btn-success" id="stopwatch_btn">শুরু</button>
                        <button onclick="resetTime()" class="btn-warning">রিসেট</button>
                    </div>
                </div>

                <!-- Match Status -->
                <div class="card mb-6">
                    <h2 class="text-xl font-semibold mb-4">ম্যাচের অবস্থা</h2>
                    <div class="flex space-x-4">
                        <button onclick="updateStatus('pending')" 
                                class="btn-warning {{ $match->status === 'pending' ? 'opacity-50' : '' }}">
                            অপেক্ষমান
                        </button>
                        <button onclick="updateStatus('live')" 
                                class="btn-success {{ $match->status === 'live' ? 'opacity-50' : '' }}">
                            লাইভ
                        </button>
                        <button onclick="updateStatus('finished')" 
                                class="btn-danger {{ $match->status === 'finished' ? 'opacity-50' : '' }}">
                            সমাপ্ত
                        </button>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">
                        বর্তমান অবস্থা: <span class="font-semibold">{{ ucfirst($match->status) }}</span>
                    </p>
                </div>

                <!-- Add Event -->
                <div class="card">
                    <h2 class="text-xl font-semibold mb-4">ইভেন্ট যোগ করুন</h2>
                    <form id="event_form" onsubmit="addEvent(event)">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">ইভেন্ট টাইপ</label>
                                <select name="event_type" class="mt-1 block w-full border rounded-md px-3 py-2">
                                    <option value="goal">গোল ⚽</option>
                                    <option value="yellow_card">হলুদ কার্ড 🟨</option>
                                    <option value="red_card">লাল কার্ড 🟥</option>
                                    <option value="substitution">খেলোয়াড় পরিবর্তন 🔄</option>
                                    <option value="penalty">পেনাল্টি 🥅</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">টিম</label>
                                <select name="team" class="mt-1 block w-full border rounded-md px-3 py-2">
                                    <option value="team_a">{{ $match->team_a }}</option>
                                    <option value="team_b">{{ $match->team_b }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">খেলোয়াড়</label>
                                <input type="text" name="player" class="mt-1 block w-full border rounded-md px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">মিনিট</label>
                                <input type="number" name="minute" class="mt-1 block w-full border rounded-md px-3 py-2" min="0">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">বিবরণ</label>
                            <textarea name="description" rows="2" class="mt-1 block w-full border rounded-md px-3 py-2"></textarea>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn-primary">ইভেন্ট যোগ করুন</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Overlay Link -->
                <div class="card">
                    <h2 class="text-xl font-semibold mb-4">ওভারলে লিংক</h2>
                    @if($overlayToken)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">ওভারলে URL</label>
                        <div class="mt-1 flex">
                            <input type="text" id="overlay_url" value="{{ route('overlay.show', $overlayToken->token) }}" 
                                   class="flex-1 border rounded-l-md px-3 py-2 text-sm" readonly>
                            <button onclick="copyOverlayUrl()" class="bg-blue-500 text-white px-3 py-2 rounded-r-md">কপি</button>
                        </div>
                    </div>
                    @endif
                    <button onclick="generateOverlayLink()" class="btn-primary w-full">
                        {{ $overlayToken ? 'নতুন লিংক তৈরি করুন' : 'ওভারলে লিংক তৈরি করুন' }}
                    </button>
                </div>

                <!-- Match Events -->
                <div class="card">
                    <h2 class="text-xl font-semibold mb-4">ম্যাচ ইভেন্ট</h2>
                    <div id="events_list" class="space-y-2 max-h-96 overflow-y-auto">
                        @forelse($events as $event)
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                            <div class="flex items-center space-x-2">
                                <span class="text-lg">{{ $event->getEventIcon() }}</span>
                                <div>
                                    <p class="text-sm font-medium">{{ $event->player ?: 'Unknown' }}</p>
                                    <p class="text-xs text-gray-600">{{ $event->minute }}' - {{ $event->team === 'team_a' ? $match->team_a : $match->team_b }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center py-4">কোনো ইভেন্ট নেই</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let matchId = {{ $match->id }};
        let stopwatchInterval;
        let isRunning = false;
        let currentTime = {{ $match->match_time }};

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
            });
        }

        function updateTime() {
            const timeInput = document.getElementById('time_input');
            const newTime = parseInt(timeInput.value) || 0;
            
            document.getElementById('match_time').textContent = newTime + "'";
            currentTime = newTime;
            
            fetch(`/matches/${matchId}/update-time`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ match_time: newTime })
            });
        }

        function startStopwatch() {
            const btn = document.getElementById('stopwatch_btn');
            
            if (isRunning) {
                clearInterval(stopwatchInterval);
                btn.textContent = 'শুরু';
                btn.className = 'btn-success';
                isRunning = false;
            } else {
                stopwatchInterval = setInterval(() => {
                    currentTime++;
                    document.getElementById('match_time').textContent = currentTime + "'";
                    document.getElementById('time_input').value = currentTime;
                }, 60000); // Update every minute
                
                btn.textContent = 'স্টপ';
                btn.className = 'btn-danger';
                isRunning = true;
            }
        }

        function resetTime() {
            currentTime = 0;
            document.getElementById('match_time').textContent = "0'";
            document.getElementById('time_input').value = 0;
            updateTime();
        }

        function updateStatus(status) {
            fetch(`/matches/${matchId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: status })
            }).then(() => {
                location.reload();
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
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }

        function generateOverlayLink() {
            fetch(`/matches/${matchId}/generate-overlay-link`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(response => response.json())
            .then(data => {
                document.getElementById('overlay_url').value = data.overlay_url;
            });
        }

        function copyOverlayUrl() {
            const urlInput = document.getElementById('overlay_url');
            urlInput.select();
            document.execCommand('copy');
            alert('লিংক কপি হয়েছে!');
        }
    </script>
</x-app-layout>