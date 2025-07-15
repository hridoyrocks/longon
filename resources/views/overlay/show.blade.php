<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Scoreboard Overlay - Professional</title>
    
    <!-- Bangla Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
            font-feature-settings: 'cv02','cv03','cv04','cv11';
        }

        .bangla-text {
            font-family: 'Hind Siliguri', sans-serif;
            font-weight: 500;
        }

        /* Main Scoreboard Container */
        .scoreboard {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 24px 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            min-width: 420px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 1000;
            animation: slideDown 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(-40px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0) scale(1);
            }
        }

        /* Header Section */
        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .live-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #ef4444;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .live-dot {
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.2); }
        }

        /* Teams Section */
        .teams {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 24px;
            align-items: center;
            margin-bottom: 20px;
        }

        .team {
            text-align: center;
            color: white;
        }

        .team-name {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            color: rgba(255, 255, 255, 0.9);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .team-score {
            font-size: 48px;
            font-weight: 700;
            line-height: 1;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .team-score.updated {
            animation: scoreUpdate 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes scoreUpdate {
            0% { transform: scale(1); }
            50% { transform: scale(1.15); color: #3b82f6; }
            100% { transform: scale(1); }
        }

        .vs {
            font-size: 18px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Match Info Section */
        .match-info {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            margin-bottom: 16px;
        }

        .match-time {
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .match-status {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-live {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .status-finished {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .status-pending {
            background: rgba(251, 191, 36, 0.2);
            color: #fbbf24;
            border: 1px solid rgba(251, 191, 36, 0.3);
        }

        /* Events Section */
        .events {
            max-height: 100px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
        }

        .events::-webkit-scrollbar {
            width: 4px;
        }

        .events::-webkit-scrollbar-track {
            background: transparent;
        }

        .events::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }

        .event {
            background: rgba(255, 255, 255, 0.08);
            margin: 6px 0;
            padding: 12px 16px;
            border-radius: 12px;
            color: white;
            font-size: 14px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            animation: slideInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .event-icon {
            font-size: 16px;
            width: 24px;
            text-align: center;
        }

        .event-time {
            font-weight: 600;
            color: #3b82f6;
            min-width: 35px;
        }

        .event-details {
            flex: 1;
        }

        .event-player {
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
        }

        .event-team {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
            margin-left: 8px;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 12px;
            z-index: 1001;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .watermark a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }

        /* Update Indicator */
        .update-indicator {
            position: fixed;
            top: 10px;
            right: 20px;
            background: rgba(34, 197, 94, 0.9);
            color: white;
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1002;
            backdrop-filter: blur(10px);
        }

        .update-indicator.show {
            opacity: 1;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .scoreboard {
                min-width: 320px;
                padding: 20px 24px;
                top: 10px;
                margin: 0 10px;
                left: 10px;
                right: 10px;
                transform: none;
            }
            
            .teams {
                gap: 16px;
            }
            
            .team-score {
                font-size: 36px;
            }
            
            .team-name {
                font-size: 14px;
            }
            
            .vs {
                font-size: 16px;
            }
        }

        /* Enhanced Animations */
        .scoreboard:hover {
            transform: translateX(-50%) translateY(-2px);
            box-shadow: 0 32px 64px -12px rgba(0, 0, 0, 0.5);
        }

        .team:hover .team-score {
            color: #3b82f6;
        }

        /* Goal Celebration Animation */
        @keyframes goalCelebration {
            0% { transform: scale(1); }
            25% { transform: scale(1.1) rotate(2deg); }
            50% { transform: scale(1.15) rotate(-2deg); }
            75% { transform: scale(1.1) rotate(1deg); }
            100% { transform: scale(1); }
        }

        .goal-animation {
            animation: goalCelebration 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Loading State */
        .loading {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
        }

        .loading-dots {
            display: flex;
            gap: 4px;
        }

        .loading-dot {
            width: 6px;
            height: 6px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            animation: loadingDot 1.5s infinite;
        }

        .loading-dot:nth-child(2) { animation-delay: 0.2s; }
        .loading-dot:nth-child(3) { animation-delay: 0.4s; }

        @keyframes loadingDot {
            0%, 80%, 100% { transform: scale(1); opacity: 0.6; }
            40% { transform: scale(1.2); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="update-indicator" id="updateIndicator">
        <span class="bangla-text">আপডেট হচ্ছে...</span>
    </div>

    <div class="scoreboard" id="scoreboard">
        <!-- Header -->
        <div class="header">
            <div class="live-indicator" id="liveIndicator">
                <div class="live-dot"></div>
                <span class="bangla-text">লাইভ ম্যাচ</span>
            </div>
        </div>
        
        <!-- Teams Section -->
        <div class="teams">
            <div class="team">
                <div class="team-name bangla-text" id="teamAName">বাংলাদেশ</div>
                <div class="team-score" id="teamAScore">2</div>
            </div>
            <div class="vs">VS</div>
            <div class="team">
                <div class="team-name bangla-text" id="teamBName">ভারত</div>
                <div class="team-score" id="teamBScore">1</div>
            </div>
        </div>
        
        <!-- Match Info -->
        <div class="match-info">
            <div class="match-time bangla-text" id="matchTime">৪৫'</div>
            <div class="match-status status-live bangla-text" id="matchStatus">
                লাইভ
            </div>
        </div>
        
        <!-- Events -->
        <div class="events" id="eventsContainer">
            <div class="event">
                <div class="event-icon">⚽</div>
                <div class="event-time">৪৩'</div>
                <div class="event-details">
                    <span class="event-player bangla-text">সাকিব আল হাসান</span>
                    <span class="event-team bangla-text">(বাংলাদেশ)</span>
                </div>
            </div>
            <div class="event">
                <div class="event-icon">🟨</div>
                <div class="event-time">৩৮'</div>
                <div class="event-details">
                    <span class="event-player bangla-text">বিরাট কোহলি</span>
                    <span class="event-team bangla-text">(ভারত)</span>
                </div>
            </div>
            <div class="event">
                <div class="event-icon">⚽</div>
                <div class="event-time">২৫'</div>
                <div class="event-details">
                    <span class="event-player bangla-text">তামিম ইকবাল</span>
                    <span class="event-team bangla-text">(বাংলাদেশ)</span>
                </div>
            </div>
        </div>
    </div>

    <div class="watermark">
        <span class="bangla-text">Powered by</span> <a href="https://scorestream.pro" target="_blank">ScoreStream Pro</a>
    </div>

    <script>
        // Demo data - Replace with real API calls
        const matchData = {
            id: 1,
            teamA: 'বাংলাদেশ',
            teamB: 'ভারত',
            teamAScore: 2,
            teamBScore: 1,
            matchTime: 45,
            status: 'live',
            events: [
                { minute: 43, type: 'goal', player: 'সাকিব আল হাসান', team: 'বাংলাদেশ', icon: '⚽' },
                { minute: 38, type: 'yellow_card', player: 'বিরাট কোহলি', team: 'ভারত', icon: '🟨' },
                { minute: 25, type: 'goal', player: 'তামিম ইকবাল', team: 'বাংলাদেশ', icon: '⚽' }
            ]
        };

        let previousData = { ...matchData };
        let isLoading = false;

        // Initialize overlay
        function initializeOverlay() {
            updateDisplay(matchData);
            startAutoUpdate();
        }

        // Update display with new data
        function updateDisplay(data) {
            // Update team names
            document.getElementById('teamAName').textContent = data.teamA;
            document.getElementById('teamBName').textContent = data.teamB;

            // Update scores with animation
            updateScore('teamAScore', data.teamAScore, previousData.teamAScore);
            updateScore('teamBScore', data.teamBScore, previousData.teamBScore);

            // Update match time
            document.getElementById('matchTime').textContent = data.matchTime + "'";

            // Update status
            updateStatus(data.status);

            // Update events
            updateEvents(data.events);

            // Update live indicator
            updateLiveIndicator(data.status);
        }

        // Update score with animation
        function updateScore(elementId, newScore, oldScore) {
            const element = document.getElementById(elementId);
            
            if (newScore !== oldScore) {
                element.classList.add('updated');
                element.textContent = newScore;
                
                // Goal celebration for score increase
                if (newScore > oldScore) {
                    document.getElementById('scoreboard').classList.add('goal-animation');
                    setTimeout(() => {
                        document.getElementById('scoreboard').classList.remove('goal-animation');
                    }, 800);
                }
                
                setTimeout(() => {
                    element.classList.remove('updated');
                }, 600);
            }
        }

        // Update match status
        function updateStatus(status) {
            const statusElement = document.getElementById('matchStatus');
            statusElement.className = `match-status status-${status} bangla-text`;
            
            const statusText = {
                'live': 'লাইভ',
                'finished': 'সমাপ্ত',
                'pending': 'অপেক্ষমান'
            };
            
            statusElement.textContent = statusText[status] || 'অজানা';
        }

        // Update live indicator
        function updateLiveIndicator(status) {
            const indicator = document.getElementById('liveIndicator');
            
            if (status === 'live') {
                indicator.style.display = 'flex';
                indicator.innerHTML = `
                    <div class="live-dot"></div>
                    <span class="bangla-text">লাইভ ম্যাচ</span>
                `;
            } else if (status === 'finished') {
                indicator.style.display = 'flex';
                indicator.innerHTML = `
                    <div style="width: 8px; height: 8px; background: #22c55e; border-radius: 50%;"></div>
                    <span class="bangla-text">সমাপ্ত</span>
                `;
            } else {
                indicator.style.display = 'none';
            }
        }

        // Update events
        function updateEvents(events) {
            const container = document.getElementById('eventsContainer');
            container.innerHTML = '';
            
            events.slice(0, 3).forEach(event => {
                const eventDiv = document.createElement('div');
                eventDiv.className = 'event';
                eventDiv.innerHTML = `
                    <div class="event-icon">${event.icon}</div>
                    <div class="event-time">${event.minute}'</div>
                    <div class="event-details">
                        <span class="event-player bangla-text">${event.player}</span>
                        <span class="event-team bangla-text">(${event.team})</span>
                    </div>
                `;
                container.appendChild(eventDiv);
            });
        }

        // Show update indicator
        function showUpdateIndicator() {
            const indicator = document.getElementById('updateIndicator');
            indicator.classList.add('show');
            setTimeout(() => {
                indicator.classList.remove('show');
            }, 2000);
        }

        // Auto-update functionality
        function startAutoUpdate() {
            setInterval(() => {
                if (!isLoading) {
                    updateOverlayData();
                }
            }, 5000); // Update every 5 seconds
        }

        // Simulate API call for updates
        function updateOverlayData() {
            isLoading = true;
            showUpdateIndicator();
            
            // Simulate API call delay
            setTimeout(() => {
                // Simulate random updates
                const hasUpdates = Math.random() > 0.7;
                
                if (hasUpdates) {
                    // Simulate score change
                    if (Math.random() > 0.5) {
                        matchData.teamAScore += Math.random() > 0.5 ? 1 : 0;
                        matchData.teamBScore += Math.random() > 0.5 ? 1 : 0;
                    }
                    
                    // Simulate time progression
                    if (matchData.status === 'live') {
                        matchData.matchTime += Math.floor(Math.random() * 3);
                    }
                    
                    // Update display
                    updateDisplay(matchData);
                    previousData = { ...matchData };
                }
                
                isLoading = false;
            }, 1000);
        }

        // Smart timer system
        function startSmartTimer() {
            if (matchData.status === 'live') {
                setInterval(() => {
                    matchData.matchTime += 1;
                    document.getElementById('matchTime').textContent = matchData.matchTime + "'";
                }, 60000); // Increment every minute
            }
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeOverlay();
            startSmartTimer();
        });

        // Handle visibility change (pause when tab is hidden)
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                // Pause updates when tab is hidden
                isLoading = true;
            } else {
                // Resume updates when tab is visible
                isLoading = false;
                updateOverlayData();
            }
        });
    </script>
</body>
</html>