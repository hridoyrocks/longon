{{-- resources/views/overlay/show.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Scoreboard Overlay</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Hind Siliguri', sans-serif;
            background: transparent;
            overflow: hidden;
            user-select: none;
        }

        .bangla-text {
            font-family: 'Hind Siliguri', sans-serif;
        }

        /* Enhanced Scoreboard Container */
        .scoreboard {
            position: fixed;
            top: 25px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: stretch;
            height: 70px;
            z-index: 1000;
            animation: slideDown 1s ease-out;
            filter: drop-shadow(0 10px 30px rgba(0, 0, 0, 0.4));
            width: 85vw;
            min-width: 700px;
            max-width: 1400px;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0a0a0a 100%);
            border-radius: 8px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            box-shadow: 
                0 0 60px rgba(0, 255, 255, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(-50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0) scale(1);
            }
        }

        /* Team Sections */
        .team-section {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6, #1e3a8a);
            color: white;
            padding: 0 35px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: clamp(20px, 2.8vw, 26px);
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            flex: 1;
            min-width: 0;
            position: relative;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: all 0.4s ease;
            border-top: 3px solid #60a5fa;
            border-bottom: 3px solid #60a5fa;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        .team-section.home {
            border-left: 3px solid #60a5fa;
            border-radius: 6px 0 0 6px;
            background: linear-gradient(135deg, #dc2626, #ef4444, #dc2626);
        }

        .team-section.away {
            border-right: 3px solid #60a5fa;
            border-radius: 0 6px 6px 0;
            background: linear-gradient(135deg, #059669, #10b981, #059669);
        }

        /* Score Section */
        .score-section {
            background: linear-gradient(135deg, #000000, #1f1f1f, #000000);
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: clamp(36px, 5vw, 48px);
            font-weight: 900;
            min-width: 160px;
            width: 160px;
            position: relative;
            flex-shrink: 0;
            border: 3px solid #374151;
            font-feature-settings: 'tnum';
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
        }

        .score-display {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .score {
            transition: all 0.5s ease;
            font-family: 'Inter', monospace;
            text-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
        }

        .score.updated {
            animation: scoreUpdate 0.8s ease;
        }

        @keyframes scoreUpdate {
            0% { 
                transform: scale(1); 
                color: white; 
            }
            25% { 
                transform: scale(1.3); 
                color: #fbbf24; 
            }
            50% { 
                transform: scale(1.1); 
                color: #22c55e; 
            }
            100% { 
                transform: scale(1); 
                color: white; 
            }
        }

        .score-divider {
            font-size: clamp(28px, 4vw, 36px);
            color: #9ca3af;
            font-weight: 400;
        }

        /* Timer Section */
        .timer-section {
            background: linear-gradient(135deg, #7c2d12, #dc2626, #7c2d12);
            color: white;
            padding: 8px 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: clamp(14px, 1.8vw, 18px);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border-radius: 0 0 6px 6px;
            position: absolute;
            bottom: -35px;
            left: 50%;
            transform: translateX(-50%);
            min-width: 120px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
        }

        .timer-display {
            font-family: 'Inter', monospace;
            font-size: clamp(16px, 2vw, 20px);
            font-weight: 800;
            color: #fff;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
        }

        /* Live Indicator */
        .live-indicator {
            position: absolute;
            top: -12px;
            right: 20px;
            background: linear-gradient(135deg, #dc2626, #ef4444);
            color: white;
            padding: 6px 15px;
            border-radius: 6px;
            font-size: clamp(10px, 1.4vw, 12px);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            animation: livePulse 2s infinite;
            z-index: 10;
            box-shadow: 
                0 4px 15px rgba(220, 38, 38, 0.4),
                0 0 20px rgba(220, 38, 38, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        @keyframes livePulse {
            0%, 100% { 
                opacity: 1; 
                transform: scale(1);
            }
            50% { 
                opacity: 0.8; 
                transform: scale(1.05);
            }
        }

        /* Goal Celebration */
        .goal-celebration {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: radial-gradient(circle, rgba(34, 197, 94, 0.3) 0%, rgba(34, 197, 94, 0.1) 50%, transparent 70%);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            pointer-events: none;
        }

        .goal-text {
            font-size: clamp(100px, 20vw, 250px);
            font-weight: 900;
            color: #22c55e;
            text-transform: uppercase;
            letter-spacing: 12px;
            text-shadow: 
                0 0 40px rgba(34, 197, 94, 0.8),
                0 0 80px rgba(34, 197, 94, 0.6);
            animation: goalAnimation 4s ease-out;
        }

        @keyframes goalAnimation {
            0% {
                transform: scale(0.3);
                opacity: 0;
            }
            15% {
                transform: scale(1.3);
                opacity: 1;
            }
            30% {
                transform: scale(0.9);
                opacity: 1;
            }
            45% {
                transform: scale(1.1);
                opacity: 1;
            }
            60% {
                transform: scale(1);
                opacity: 1;
            }
            85% {
                transform: scale(1);
                opacity: 1;
            }
            100% {
                transform: scale(1.1);
                opacity: 0;
            }
        }

        /* Events Ticker */
        .events-ticker {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            width: 80vw;
            max-width: 1200px;
            height: 50px;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.9), rgba(30, 30, 30, 0.9));
            border-radius: 25px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            z-index: 999;
            display: none;
        }

        .events-ticker.show {
            display: block;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }

        .events-scroll {
            display: flex;
            align-items: center;
            height: 100%;
            padding: 0 20px;
            animation: scroll 15s linear infinite;
            gap: 40px;
        }

        @keyframes scroll {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }

        .event-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.9), rgba(30, 30, 30, 0.9));
            color: white;
            padding: 15px 20px;
            border-radius: 12px;
            font-size: 13px;
            z-index: 1001;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
            width: 120px;
            min-height: 95px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .watermark-logo {
            width: 65px;
            height: 65px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            font-weight: 900;
            color: white;
            flex-shrink: 0;
        }

        .watermark-text {
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            color: #cbd5e1;
            line-height: 1.3;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .scoreboard {
                height: 60px;
                width: 95vw;
                min-width: 450px;
                top: 20px;
            }
            
            .team-section {
                padding: 0 25px;
            }
            
            .score-section {
                min-width: 140px;
                width: 140px;
            }
            
            .timer-section {
                bottom: -28px;
                padding: 5px 20px;
                min-width: 100px;
            }
        }
    </style>
</head>
<body>
    <!-- Goal Celebration Overlay -->
    <div class="goal-celebration" id="goalCelebration">
        <div class="goal-text bangla-text" id="goalText">GOAL!</div>
    </div>

    <!-- Main Scoreboard -->
    <div class="scoreboard" id="scoreboard">
        <!-- Live Indicator -->
        <div class="live-indicator" id="liveIndicator">
            <span class="bangla-text">
                @if($match->status === 'live') LIVE
                @elseif($match->status === 'finished') FINISHED
                @elseif($match->status === 'pending') PENDING
                @endif
            </span>
        </div>

        <!-- Home Team -->
        <div class="team-section home">
            <span class="bangla-text" id="teamAName">{{ $match->team_a }}</span>
        </div>

        <!-- Score Section -->
        <div class="score-section">
            <div class="score-display">
                <span class="score" id="teamAScore">{{ $match->team_a_score }}</span>
                <span class="score-divider">-</span>
                <span class="score" id="teamBScore">{{ $match->team_b_score }}</span>
            </div>
        </div>

        <!-- Away Team -->
        <div class="team-section away">
            <span class="bangla-text" id="teamBName">{{ $match->team_b }}</span>
        </div>

        <!-- Timer Section -->
        <div class="timer-section" id="timerSection">
            <div class="timer-display" id="matchTimer">{{ sprintf('%02d:%02d', floor($match->match_time), ($match->match_time * 60) % 60) }}</div>
        </div>
    </div>

    <!-- Events Ticker -->
    <div class="events-ticker" id="eventsTicker" @if($events->count() > 0) style="display: block;" @endif>
        <div class="events-scroll" id="eventsScroll">
            @foreach($events as $event)
            <div class="event-item">
                <span>{{ $event['minute'] }}' - {{ $event['player'] }} ({{ $event['team'] }})</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Watermark -->
    @if($showWatermark)
    <div class="watermark">
        <div class="watermark-logo">SS</div>
        <div class="watermark-text bangla-text">ScoreStream Pro</div>
    </div>
    @endif

    <script>
        // Pass data from Laravel to JavaScript
        window.overlayToken = '{{ $overlayToken->token ?? "demo" }}';
        window.matchId = {{ $match->id ?? 1 }};
        window.matchData = {
            teamA: '{{ $match->team_a }}',
            teamB: '{{ $match->team_b }}',
            teamAScore: {{ $match->team_a_score }},
            teamBScore: {{ $match->team_b_score }},
            matchTimeMinutes: {{ floor($match->match_time) }},
            matchTimeSeconds: {{ ($match->match_time * 60) % 60 }},
            status: '{{ $match->status }}',
            events: @json($events->toArray())
        };

        // Configuration
        const matchToken = window.overlayToken;
        const matchId = window.matchId;
        let matchData = window.matchData;

        let previousScores = {
            teamA: matchData.teamAScore,
            teamB: matchData.teamBScore
        };

        let timer = {
            minutes: matchData.matchTimeMinutes,
            seconds: matchData.matchTimeSeconds,
            interval: null,
            isRunning: false
        };

        // Goal Celebration
        function showGoalCelebration(team) {
            const celebration = document.getElementById('goalCelebration');
            const goalText = document.getElementById('goalText');
            
            const teamName = team === 'teamA' ? matchData.teamA : matchData.teamB;
            goalText.textContent = `GOAL! ${teamName}`;
            
            celebration.style.display = 'flex';
            
            setTimeout(() => {
                celebration.style.display = 'none';
            }, 4000);
        }

        // Timer Functions
        function startTimer() {
            if (timer.interval) clearInterval(timer.interval);
            
            timer.interval = setInterval(() => {
                if (timer.isRunning && matchData.status === 'live') {
                    timer.seconds++;
                    if (timer.seconds >= 60) {
                        timer.seconds = 0;
                        timer.minutes++;
                    }
                    updateTimerDisplay();
                }
            }, 1000);
        }

        function updateTimerDisplay() {
            const display = document.getElementById('matchTimer');
            const minutes = timer.minutes.toString().padStart(2, '0');
            const seconds = timer.seconds.toString().padStart(2, '0');
            display.textContent = `${minutes}:${seconds}`;
        }

        function setTimer(minutes, seconds) {
            timer.minutes = minutes;
            timer.seconds = seconds;
            updateTimerDisplay();
        }

        // Update Display Function
        function updateDisplay(data) {
            document.getElementById('teamAName').textContent = data.teamA;
            document.getElementById('teamBName').textContent = data.teamB;
            
            // Check for goals
            if (data.teamAScore > previousScores.teamA) {
                showGoalCelebration('teamA');
            }
            if (data.teamBScore > previousScores.teamB) {
                showGoalCelebration('teamB');
            }
            
            // Update scores
            updateScore('teamAScore', data.teamAScore);
            updateScore('teamBScore', data.teamBScore);
            
            previousScores.teamA = data.teamAScore;
            previousScores.teamB = data.teamBScore;
            
            // Update timer
            if (data.matchTimeMinutes !== undefined && data.matchTimeSeconds !== undefined) {
                setTimer(data.matchTimeMinutes, data.matchTimeSeconds);
            }
            
            // Update live indicator
            updateLiveIndicator(data.status);
            
            // Update events ticker
            updateEventsTicker(data.events);
        }

        function updateScore(elementId, newScore) {
            const element = document.getElementById(elementId);
            if (element.textContent != newScore) {
                element.classList.add('updated');
                element.textContent = newScore;
                
                setTimeout(() => {
                    element.classList.remove('updated');
                }, 800);
            }
        }

        function updateLiveIndicator(status) {
            const indicator = document.getElementById('liveIndicator');
            
            switch (status) {
                case 'live':
                    indicator.style.display = 'block';
                    indicator.innerHTML = '<span class="bangla-text">LIVE</span>';
                    indicator.style.background = 'linear-gradient(135deg, #dc2626, #ef4444)';
                    timer.isRunning = true;
                    break;
                case 'finished':
                    indicator.style.display = 'block';
                    indicator.style.background = 'linear-gradient(135deg, #059669, #10b981)';
                    indicator.innerHTML = '<span class="bangla-text">FINISHED</span>';
                    timer.isRunning = false;
                    break;
                case 'pending':
                    indicator.style.display = 'block';
                    indicator.style.background = 'linear-gradient(135deg, #d97706, #f59e0b)';
                    indicator.innerHTML = '<span class="bangla-text">PENDING</span>';
                    timer.isRunning = false;
                    break;
                default:
                    indicator.style.display = 'none';
                    timer.isRunning = false;
            }
        }

        function updateEventsTicker(events) {
            if (!events || events.length === 0) return;
            
            const ticker = document.getElementById('eventsTicker');
            const scroll = document.getElementById('eventsScroll');
            
            scroll.innerHTML = '';
            
            const recentEvents = events.slice(-5).reverse();
            recentEvents.forEach(event => {
                const eventItem = document.createElement('div');
                eventItem.className = 'event-item';
                eventItem.innerHTML = `<span>${event.minute}' - ${event.player} (${event.team})</span>`;
                scroll.appendChild(eventItem);
            });
            
            if (recentEvents.length > 0) {
                ticker.classList.add('show');
            }
        }

        // Real-time data fetching
        async function fetchMatchData() {
            try {
                const response = await fetch(`/api/overlay-data/${matchId}`);
                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        updateDisplay(data.match);
                    }
                }
            } catch (error) {
                console.error('Error fetching match data:', error);
            }
        }

        // Initialize overlay
        function initializeOverlay() {
            updateDisplay(matchData);
            updateTimerDisplay();
            startTimer();
            
            // Auto-update every 3 seconds
            setInterval(fetchMatchData, 3000);
        }

        // Start when page loads
        document.addEventListener('DOMContentLoaded', initializeOverlay);
    </script>
</body>
</html>