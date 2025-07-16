<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Scoreboard Overlay</title>
    
    <!-- Bangla Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
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
        }

        .bangla-text {
            font-family: 'Hind Siliguri', sans-serif;
        }

        /* Main Scoreboard Container */
        .scoreboard {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: stretch;
            height: 60px;
            z-index: 1000;
            animation: slideDown 0.8s ease-out;
            filter: drop-shadow(0 8px 25px rgba(0, 0, 0, 0.3));
            width: 80vw;
            min-width: 600px;
            max-width: 1200px;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(-40px);
            }
            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }

        /* Team Sections */
        .team-section {
            background: linear-gradient(135deg, #1e40af, #1d4ed8);
            color: white;
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: clamp(22px, 3vw, 28px);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            flex: 1;
            min-width: 0;
            position: relative;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: all 0.3s ease;
            border-top: 2px solid #3b82f6;
            border-bottom: 2px solid #3b82f6;
        }

        .team-section.home {
            border-left: 2px solid #3b82f6;
            border-radius: 4px 0 0 4px;
        }

        .team-section.away {
            border-right: 2px solid #3b82f6;
            border-radius: 0 4px 4px 0;
        }

        /* Score Section */
        .score-section {
            background: linear-gradient(135deg, #111827, #1f2937);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: clamp(32px, 4.5vw, 40px);
            font-weight: 900;
            min-width: 120px;
            width: 120px;
            position: relative;
            flex-shrink: 0;
            border: 2px solid #374151;
            font-feature-settings: 'tnum';
        }

        .score {
            transition: all 0.4s ease;
            font-family: 'Inter', monospace;
        }

        .score.updated {
            animation: scoreUpdate 0.6s ease;
        }

        @keyframes scoreUpdate {
            0% { transform: scale(1); color: white; }
            50% { transform: scale(1.2); color: #fbbf24; }
            100% { transform: scale(1); color: white; }
        }

        .score-divider {
            margin: 0 8px;
            font-size: clamp(24px, 3.5vw, 28px);
            color: #6b7280;
            font-weight: 400;
        }

        /* Live indicator */
        .live-indicator {
            position: absolute;
            top: -8px;
            right: 15px;
            background: #dc2626;
            color: white;
            padding: 4px 10px;
            border-radius: 3px;
            font-size: clamp(9px, 1.2vw, 11px);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            animation: livePulse 2s infinite;
            z-index: 10;
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.4);
        }

        @keyframes livePulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        /* Tournament/Timer Section */
        .tournament-section {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            padding: 8px 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: clamp(12px, 1.6vw, 14px);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 0 0 4px 4px;
            position: absolute;
            bottom: -28px;
            left: 50%;
            transform: translateX(-50%);
            min-width: 100px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* Goal Celebration Overlay */
        .goal-celebration {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: radial-gradient(circle, rgba(34, 197, 94, 0.2) 0%, transparent 70%);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            pointer-events: none;
        }

        .goal-text {
            font-size: clamp(80px, 15vw, 200px);
            font-weight: 900;
            color: #22c55e;
            text-transform: uppercase;
            letter-spacing: 8px;
            text-shadow: 0 0 30px rgba(34, 197, 94, 0.8);
            animation: goalAnimation 3s ease-out;
        }

        @keyframes goalAnimation {
            0% {
                transform: scale(0.5);
                opacity: 0;
            }
            20% {
                transform: scale(1.2);
                opacity: 1;
            }
            40% {
                transform: scale(0.9);
                opacity: 1;
            }
            60% {
                transform: scale(1.1);
                opacity: 1;
            }
            80% {
                transform: scale(1);
                opacity: 1;
            }
            100% {
                transform: scale(1);
                opacity: 0;
            }
        }

        /* Confetti particles */
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background: #fbbf24;
            animation: confettiFall 3s linear;
        }

        @keyframes confettiFall {
            0% {
                transform: translateY(-100vh) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(360deg);
                opacity: 0;
            }
        }

        /* Watermark */
        .watermark {
            position: fixed;
            bottom: 25px;
            right: 25px;
            background: rgba(15, 23, 42, 0.9);
            color: white;
            padding: clamp(12px, 1.8vw, 16px) clamp(16px, 2.2vw, 20px);
            border-radius: clamp(8px, 1.2vw, 10px);
            font-size: clamp(10px, 1.2vw, 12px);
            z-index: 1001;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: clamp(6px, 1vw, 8px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            width: clamp(80px, 10vw, 110px);
            min-height: clamp(70px, 8vw, 85px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .watermark-logo {
            width: clamp(40px, 6vw, 60px);
            height: clamp(40px, 6vw, 60px);
            background: url('/storage/logo/watermark-logo.png') no-repeat center;
            background-size: contain;
            border-radius: clamp(6px, 1vw, 8px);
            flex-shrink: 0;
        }

        .watermark-text {
            font-size: clamp(9px, 1.1vw, 11px);
            font-weight: 500;
            text-align: center;
            color: #cbd5e1;
            line-height: 1.2;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* Responsive breakpoints */
        @media (max-width: 1200px) {
            .scoreboard {
                width: 85vw;
                min-width: 500px;
            }
        }

        @media (max-width: 768px) {
            .scoreboard {
                height: 55px;
                width: 90vw;
                min-width: 400px;
                top: 15px;
            }
            
            .team-section {
                padding: 0 20px;
            }
            
            .score-section {
                min-width: 100px;
                width: 100px;
            }
            
            .tournament-section {
                bottom: -25px;
                padding: 6px 20px;
                min-width: 80px;
            }
            
            .live-indicator {
                top: -6px;
                right: 12px;
                padding: 3px 8px;
            }
        }

        @media (max-width: 480px) {
            .scoreboard {
                height: 50px;
                width: 95vw;
                min-width: 320px;
                top: 10px;
            }
            
            .team-section {
                padding: 0 15px;
            }
            
            .score-section {
                min-width: 90px;
                width: 90px;
            }
            
            .tournament-section {
                bottom: -22px;
                padding: 5px 15px;
                min-width: 70px;
            }
            
            .watermark {
                bottom: 20px;
                right: 20px;
            }
        }

        /* Ultra-wide screens */
        @media (min-width: 1920px) {
            .scoreboard {
                width: 70vw;
                min-width: 800px;
            }
        }

        /* 4K screens */
        @media (min-width: 2560px) {
            .scoreboard {
                width: 60vw;
                min-width: 1000px;
                height: 70px;
            }
            
            .score-section {
                min-width: 140px;
                width: 140px;
            }
            
            .tournament-section {
                bottom: -32px;
            }
        }
    </style>
</head>
<body>
    <!-- Goal Celebration Overlay -->
    <div class="goal-celebration" id="goalCelebration">
        <div class="goal-text bangla-text" id="goalText">GOAL!</div>
    </div>

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
            <span class="score" id="teamAScore">{{ $match->team_a_score }}</span>
            <span class="score-divider">-</span>
            <span class="score" id="teamBScore">{{ $match->team_b_score }}</span>
        </div>

        <!-- Away Team -->
        <div class="team-section away">
            <span class="bangla-text" id="teamBName">{{ $match->team_b }}</span>
        </div>

        <!-- Tournament/Timer Section -->
        <div class="tournament-section" id="tournamentSection">
            <span id="matchTimer">{{ $match->match_time }}'</span>
        </div>
    </div>

    <!-- Watermark -->
    <div class="watermark">
        <div class="watermark-logo"></div>
        <div class="watermark-text bangla-text">Powered by</div>
    </div>

    <script>
        // Configuration
        const matchToken = '{{ $overlayToken->token ?? "demo" }}';
        const matchId = {{ $match->id ?? 1 }};
        
        // Current match data - Real data from backend
        let matchData = {
            teamA: '{{ $match->team_a }}',
            teamB: '{{ $match->team_b }}',
            teamAScore: {{ $match->team_a_score }},
            teamBScore: {{ $match->team_b_score }},
            matchTime: {{ $match->match_time }},
            status: '{{ $match->status }}'
        };

        let previousScores = {
            teamA: matchData.teamAScore,
            teamB: matchData.teamBScore
        };

        // Goal celebration function
        function showGoalCelebration(team) {
            const celebration = document.getElementById('goalCelebration');
            const goalText = document.getElementById('goalText');
            
            // Set goal text
            goalText.textContent = 'GOAL!';
            goalText.className = 'goal-text bangla-text';
            
            // Show celebration
            celebration.style.display = 'flex';
            
            // Create confetti
            createConfetti();
            
            // Hide after animation
            setTimeout(() => {
                celebration.style.display = 'none';
            }, 3000);
        }

        // Create confetti particles
        function createConfetti() {
            const celebration = document.getElementById('goalCelebration');
            const colors = ['#fbbf24', '#22c55e', '#3b82f6', '#ef4444', '#8b5cf6'];
            
            for (let i = 0; i < 50; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.animationDelay = Math.random() * 2 + 's';
                celebration.appendChild(confetti);
                
                // Remove after animation
                setTimeout(() => {
                    confetti.remove();
                }, 3000);
            }
        }

        // Update display
        function updateDisplay(data) {
            document.getElementById('teamAName').textContent = data.teamA;
            document.getElementById('teamBName').textContent = data.teamB;
            
            // Check for goal before updating scores
            if (data.teamAScore > previousScores.teamA) {
                showGoalCelebration('teamA');
            }
            if (data.teamBScore > previousScores.teamB) {
                showGoalCelebration('teamB');
            }
            
            // Update scores with animation
            updateScore('teamAScore', data.teamAScore);
            updateScore('teamBScore', data.teamBScore);
            
            // Update previous scores
            previousScores.teamA = data.teamAScore;
            previousScores.teamB = data.teamBScore;
            
            // Update timer
            document.getElementById('matchTimer').textContent = data.matchTime + "'";
            
            // Update live indicator
            updateLiveIndicator(data.status);
        }

        // Update score with animation
        function updateScore(elementId, newScore) {
            const element = document.getElementById(elementId);
            if (element.textContent != newScore) {
                element.classList.add('updated');
                element.textContent = newScore;
                
                setTimeout(() => {
                    element.classList.remove('updated');
                }, 600);
            }
        }

        // Update live indicator based on real match status
        function updateLiveIndicator(status) {
            const indicator = document.getElementById('liveIndicator');
            
            if (status === 'live') {
                indicator.style.display = 'block';
                indicator.innerHTML = '<span class="bangla-text">LIVE</span>';
                indicator.style.background = '#dc2626';
            } else if (status === 'finished') {
                indicator.style.display = 'block';
                indicator.style.background = '#059669';
                indicator.innerHTML = '<span class="bangla-text">FINISHED</span>';
            } else if (status === 'pending') {
                indicator.style.display = 'block';
                indicator.style.background = '#d97706';
                indicator.innerHTML = '<span class="bangla-text">PENDING</span>';
            } else {
                indicator.style.display = 'none';
            }
        }

        // Auto-update functionality
        function startAutoUpdate() {
            // Update match data every 3 seconds for real-time feel
            setInterval(() => {
                updateOverlayData();
            }, 3000);
        }

        // Fetch updated match data
        async function updateOverlayData() {
            try {
                const response = await fetch(`/api/overlay-data/${matchId}`);
                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        updateDisplay(data.match);
                    }
                }
            } catch (error) {
                console.error('Error updating overlay data:', error);
            }
        }

        // Initialize overlay
        function initializeOverlay() {
            updateDisplay(matchData);
            startAutoUpdate();
        }

        // Start when page loads
        document.addEventListener('DOMContentLoaded', initializeOverlay);
    </script>
</body>
</html>