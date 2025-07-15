<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Scoreboard Overlay</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: transparent;
            overflow: hidden;
        }

        .scoreboard {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            min-width: 400px;
            text-align: center;
            z-index: 1000;
            animation: slideDown 0.8s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }

        .teams {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .team {
            flex: 1;
            color: white;
        }

        .team-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .team-score {
            font-size: 48px;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
        }

        .team-score.updated {
            animation: scoreUpdate 0.5s ease-in-out;
        }

        @keyframes scoreUpdate {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); color: #ffff00; }
        }

        .vs {
            font-size: 24px;
            color: white;
            margin: 0 20px;
            font-weight: bold;
        }

        .match-info {
            color: white;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .match-time {
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 15px;
            border-radius: 20px;
            display: inline-block;
            backdrop-filter: blur(10px);
        }

        .match-status {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 12px;
            margin-left: 10px;
            font-weight: bold;
        }

        .status-live {
            background: #ff4444;
            color: white;
            animation: pulse 2s infinite;
        }

        .status-finished {
            background: #44ff44;
            color: black;
        }

        .status-pending {
            background: #ffff44;
            color: black;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        .events {
            margin-top: 15px;
            max-height: 120px;
            overflow-y: auto;
            scrollbar-width: thin;
        }

        .event {
            background: rgba(255, 255, 255, 0.1);
            margin: 5px 0;
            padding: 8px;
            border-radius: 8px;
            color: white;
            font-size: 14px;
            backdrop-filter: blur(5px);
            animation: slideInRight 0.5s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .watermark {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 10px 15px;
            border-radius: 10px;
            font-size: 14px;
            z-index: 1001;
            backdrop-filter: blur(10px);
        }

        .watermark a {
            color: #4CAF50;
            text-decoration: none;
        }

        .update-indicator {
            position: fixed;
            top: 10px;
            right: 10px;
            background: rgba(0, 255, 0, 0.8);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .update-indicator.show {
            opacity: 1;
        }

        /* Responsive design */
        @media (max-width: 480px) {
            .scoreboard {
                min-width: 300px;
                padding: 15px;
                top: 10px;
            }
            
            .team-score {
                font-size: 36px;
            }
            
            .team-name {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="update-indicator" id="updateIndicator">উপডেট হচ্ছে...</div>

    <div class="scoreboard">
        <div class="teams">
            <div class="team">
                <div class="team-name">{{ $match->team_a }}</div>
                <div class="team-score" id="team_a_score">{{ $match->team_a_score }}</div>
            </div>
            <div class="vs">VS</div>
            <div class="team">
                <div class="team-name">{{ $match->team_b }}</div>
                <div class="team-score" id="team_b_score">{{ $match->team_b_score }}</div>
            </div>
        </div>
        
        <div class="match-info">
            <span class="match-time" id="match_time">{{ $match->match_time }}'</span>
            <span class="match-status status-{{ $match->status }}" id="match_status">
                @if($match->status === 'live') 🔴 LIVE
                @elseif($match->status === 'finished') ✅ FINISHED
                @else ⏳ PENDING
                @endif
            </span>
        </div>
        
        <div class="events" id="events_container">
            @foreach($match->events()->latest()->limit(3)->get() as $event)
            <div class="event">
                <strong>{{ $event->minute }}'</strong> - 
                {{ $event->getEventIcon() }} 
                {{ $event->player ?: 'Unknown' }} 
                ({{ $event->team === 'team_a' ? $match->team_a : $match->team_b }})
            </div>
            @endforeach
        </div>
    </div>

    @if($showWatermark)
    <div class="watermark">
        Powered by <a href="https://scorestream.pro" target="_blank">ScoreStream Pro</a>
    </div>
    @endif

    <script>
        const matchId = {{ $match->id }};
        let previousData = {
            team_a_score: {{ $match->team_a_score }},
            team_b_score: {{ $match->team_b_score }},
            match_time: {{ $match->match_time }},
            status: '{{ $match->status }}',
            events_count: {{ $match->events()->count() }}
        };

        // Update overlay data
        async function updateOverlay() {
            try {
                const response = await fetch(`/api/overlay-data/${matchId}`);
                const data = await response.json();
                
                if (data.success) {
                    const match = data.match;
                    let hasUpdates = false;

                    // Update scores with animation
                    if (match.team_a_score !== previousData.team_a_score) {
                        const scoreElement = document.getElementById('team_a_score');
                        scoreElement.textContent = match.team_a_score;
                        scoreElement.classList.add('updated');
                        setTimeout(() => scoreElement.classList.remove('updated'), 500);
                        hasUpdates = true;
                    }

                    if (match.team_b_score !== previousData.team_b_score) {
                        const scoreElement = document.getElementById('team_b_score');
                        scoreElement.textContent = match.team_b_score;
                        scoreElement.classList.add('updated');
                        setTimeout(() => scoreElement.classList.remove('updated'), 500);
                        hasUpdates = true;
                    }

                    // Update time
                    if (match.match_time !== previousData.match_time) {
                        document.getElementById('match_time').textContent = match.match_time + "'";
                        hasUpdates = true;
                    }

                    // Update status
                    if (match.status !== previousData.status) {
                        const statusElement = document.getElementById('match_status');
                        statusElement.className = `match-status status-${match.status}`;
                        
                        if (match.status === 'live') {
                            statusElement.innerHTML = '🔴 LIVE';
                        } else if (match.status === 'finished') {
                            statusElement.innerHTML = '✅ FINISHED';
                        } else {
                            statusElement.innerHTML = '⏳ PENDING';
                        }
                        hasUpdates = true;
                    }

                    // Update events
                    if (data.events && data.events.length !== previousData.events_count) {
                        const eventsContainer = document.getElementById('events_container');
                        eventsContainer.innerHTML = '';
                        
                        data.events.slice(0, 3).forEach(event => {
                            const eventElement = document.createElement('div');
                            eventElement.className = 'event';
                            eventElement.innerHTML = `
                                <strong>${event.minute}'</strong> - 
                                ${getEventIcon(event.event_type)} 
                                ${event.player || 'Unknown'} 
                                (${event.team === 'team_a' ? '{{ $match->team_a }}' : '{{ $match->team_b }}'})
                            `;
                            eventsContainer.appendChild(eventElement);
                        });
                        hasUpdates = true;
                    }

                    // Show update indicator
                    if (hasUpdates) {
                        showUpdateIndicator();
                    }

                    // Update previous data
                    previousData = {
                        team_a_score: match.team_a_score,
                        team_b_score: match.team_b_score,
                        match_time: match.match_time,
                        status: match.status,
                        events_count: data.events ? data.events.length : 0
                    };
                }
            } catch (error) {
                console.error('Update failed:', error);
            }
        }

        function getEventIcon(eventType) {
            const icons = {
                'goal': '⚽',
                'yellow_card': '🟨',
                'red_card': '🟥',
                'substitution': '🔄',
                'penalty': '🥅'
            };
            return icons[eventType] || '⚽';
        }

        function showUpdateIndicator() {
            const indicator = document.getElementById('updateIndicator');
            indicator.classList.add('show');
            setTimeout(() => {
                indicator.classList.remove('show');
            }, 2000);
        }

        // Start auto-update every 3 seconds
        setInterval(updateOverlay, 3000);

        // Initial load message
        console.log('Overlay loaded for match:', matchId);
    </script>
</body>
</html>