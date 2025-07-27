{{-- resources/views/overlay/show.blade.php - Enhanced World Cup Theme --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Scoreboard - World Cup Edition</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Oswald:wght@400;500;600;700&family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Oswald', sans-serif;
            background: transparent;
            overflow: hidden;
            user-select: none;
        }

        .bangla-text {
            font-family: 'Hind Siliguri', sans-serif;
        }

        /* Responsive World Cup Scoreboard */
        .scoreboard {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: stretch;
            height: 75px;
            z-index: 1000;
            animation: slideDown 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            width: 90%;
            max-width: 900px;
            min-width: 380px;
            background: #1a1a2e;
            border-radius: 18px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            transition: all 0.5s ease;
        }

        .scoreboard::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, 
                #ff0000 0%, #ff0000 16.66%,
                #ff8c00 16.66%, #ff8c00 33.33%,
                #ffd700 33.33%, #ffd700 50%,
                #00ff00 50%, #00ff00 66.66%,
                #00bfff 66.66%, #00bfff 83.33%,
                #8b00ff 83.33%, #8b00ff 100%
            );
            animation: rainbow 3s linear infinite;
        }

        /* Yellow Line Animation - REMOVED */
        /* .scoreboard::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: #ffd700;
            animation: yellowPulse 2s ease-in-out infinite;
        } */

        @keyframes yellowPulse {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }

        @keyframes rainbow {
            0% { transform: translateX(0); }
            100% { transform: translateX(100px); }
        }

        .scoreboard.update-flash {
            animation: pulse 0.5s ease;
        }

        @keyframes pulse {
            0%, 100% {
                transform: translateX(-50%) scale(1);
            }
            50% {
                transform: translateX(-50%) scale(1.05);
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(-50px) rotateX(90deg);
            }
            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0) rotateX(0);
            }
        }

        /* Team Sections - Responsive */
        .team-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            padding: 0 clamp(15px, 3vw, 30px);
        }

        .team-section.home {
            background: linear-gradient(135deg, #0066cc 0%, #004499 100%);
            clip-path: polygon(0 0, 95% 0, 100% 100%, 0 100%);
        }

        .team-section.away {
            background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%);
            clip-path: polygon(0 0, 100% 0, 100% 100%, 5% 100%);
            flex-direction: row-reverse;
        }

        .team-name {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(20px, 4vw, 32px);
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
            font-weight: 700;
            max-width: 70%;
        }

        .team-section.home .team-name {
            text-align: left;
            padding-right: 10px;
        }

        .team-section.away .team-name {
            text-align: right;
            padding-left: 10px;
        }

        .team-score {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(36px, 6vw, 48px);
            color: #ffffff;
            font-weight: 700;
            line-height: 1;
            transition: all 0.3s ease;
            min-width: clamp(45px, 8vw, 60px);
            text-align: center;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.5);
        }

        .team-score.goal-scored {
            animation: goalScore 1s ease;
        }

        @keyframes goalScore {
            0% { transform: scale(1) rotate(0deg); }
            25% { transform: scale(1.5) rotate(10deg); }
            50% { transform: scale(1.3) rotate(-10deg); }
            75% { transform: scale(1.4) rotate(5deg); }
            100% { transform: scale(1) rotate(0deg); }
        }

        /* Center Display with Live Icon */
        .score-center {
            width: clamp(90px, 15vw, 130px);
            background: #16213e;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
            /* border-left: 3px solid #ffd700; - REMOVED */
            /* border-right: 3px solid #ffd700; - REMOVED */
        }

        /* Live Icon Inside Scoreboard - REMOVED */
        /* .live-icon {
            position: absolute;
            top: 8px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 10px;
            font-weight: 700;
            color: #ff0000;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            animation: livePulse 1.5s ease-in-out infinite;
        }

        .live-dot {
            width: 6px;
            height: 6px;
            background: #ff0000;
            border-radius: 50%;
            animation: dotPulse 1.5s ease-in-out infinite;
        } */

        @keyframes livePulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        @keyframes dotPulse {
            0%, 100% { 
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 0, 0, 0.7);
            }
            50% { 
                transform: scale(1.2);
                box-shadow: 0 0 0 5px rgba(255, 0, 0, 0);
            }
        }

        .match-time {
            font-family: 'Oswald', sans-serif;
            font-size: clamp(20px, 3.5vw, 28px);
            color: #ffffff;
            font-weight: 700;
            text-align: center;
            letter-spacing: 1px;
            /* margin-top: 8px; - Removed for centered alignment */
        }

        /* Live Badge - Top of Scoreboard */
        .live-badge {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: #ff0000;
            color: #ffffff;
            padding: 5px clamp(12px, 2vw, 18px);
            border-radius: 14px;
            font-size: clamp(10px, 1.5vw, 12px);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            z-index: 10;
            border: 2px solid #ffffff;
            opacity: 0;
            animation: showHideBadge 6s ease-in-out;
        }

        @keyframes showHideBadge {
            0% { 
                opacity: 0;
                transform: translateX(-50%) translateY(-10px);
            }
            10% {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
            40% {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
            50% {
                opacity: 0;
                transform: translateX(-50%) translateY(-10px);
            }
            60% {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
            90% {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
            100% {
                opacity: 0;
                transform: translateX(-50%) translateY(-10px);
            }
        }

        /* Enhanced GOAL Celebration */
        .goal-celebration {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            display: none;
            z-index: 9999;
            pointer-events: none;
            background: radial-gradient(circle at center, rgba(255,215,0,0.2) 0%, transparent 50%);
        }

        .goal-celebration.show {
            display: block;
        }

        /* Football Animation */
        .football-container {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .football {
            position: absolute;
            width: 80px;
            height: 80px;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="45" fill="%23ffffff" stroke="%23000" stroke-width="2"/><path d="M50 5 L61 20 L61 35 L50 42 L39 35 L39 20 Z" fill="%23000"/><path d="M20 35 L31 28 L39 35 L39 50 L31 57 L20 50 Z" fill="%23000"/><path d="M80 35 L69 28 L61 35 L61 50 L69 57 L80 50 Z" fill="%23000"/><path d="M35 65 L46 58 L54 58 L65 65 L58 76 L42 76 Z" fill="%23000"/><path d="M50 58 L61 65 L61 80 L50 87 L39 80 L39 65 Z" fill="%23000"/></svg>') no-repeat center;
            background-size: contain;
            animation: footballBounce 2s ease-out;
        }

        @keyframes footballBounce {
            0% {
                transform: translate(-100px, -100px) rotate(0deg) scale(0.5);
                opacity: 0;
            }
            20% {
                transform: translate(calc(50vw - 40px), calc(50vh - 150px)) rotate(180deg) scale(1.5);
                opacity: 1;
            }
            40% {
                transform: translate(calc(50vw - 40px), calc(50vh - 40px)) rotate(360deg) scale(1.2);
            }
            50% {
                transform: translate(calc(50vw - 40px), calc(50vh + 50px)) rotate(450deg) scale(1);
            }
            60% {
                transform: translate(calc(50vw - 40px), calc(50vh - 20px)) rotate(540deg) scale(1.1);
            }
            70% {
                transform: translate(calc(50vw - 40px), calc(50vh + 20px)) rotate(630deg) scale(1);
            }
            80% {
                transform: translate(calc(50vw - 40px), calc(50vh)) rotate(720deg) scale(1);
            }
            100% {
                transform: translate(calc(100vw + 100px), calc(50vh)) rotate(1080deg) scale(0.5);
                opacity: 0;
            }
        }

        /* Multiple Footballs */
        .football:nth-child(2) {
            animation-delay: 0.3s;
            left: 20%;
        }

        .football:nth-child(3) {
            animation-delay: 0.6s;
            left: 80%;
        }

        /* Fireworks Effect */
        .fireworks {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .firework {
            position: absolute;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            animation: explode 1.5s ease-out;
        }

        @keyframes explode {
            0% {
                transform: translate(0, 0) scale(0);
                opacity: 1;
            }
            50% {
                transform: translate(var(--x), var(--y)) scale(1);
                opacity: 1;
            }
            100% {
                transform: translate(calc(var(--x) * 2), calc(var(--y) * 2)) scale(0);
                opacity: 0;
            }
        }

        /* GOAL Text - 3D Effect */
        .goal-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(120px, 20vw, 220px);
            font-weight: 900;
            color: #ffd700;
            text-transform: uppercase;
            letter-spacing: 20px;
            animation: goalText 2s ease;
            text-shadow: 
                0 0 10px #ff0000,
                0 0 20px #ff0000,
                0 0 30px #ff0000,
                0 0 40px #ff0000,
                0 0 50px #ffd700,
                0 0 60px #ffd700;
        }

        @keyframes goalText {
            0% {
                transform: translate(-50%, -50%) scale(0) rotateY(0deg) rotateX(0deg);
                opacity: 0;
            }
            20% {
                transform: translate(-50%, -50%) scale(1.5) rotateY(180deg) rotateX(10deg);
                opacity: 1;
            }
            40% {
                transform: translate(-50%, -50%) scale(1.2) rotateY(360deg) rotateX(-10deg);
            }
            60% {
                transform: translate(-50%, -50%) scale(1.3) rotateY(540deg) rotateX(10deg);
            }
            80% {
                transform: translate(-50%, -50%) scale(1.1) rotateY(720deg) rotateX(0deg);
            }
            100% {
                transform: translate(-50%, -50%) scale(0) rotateY(900deg) rotateX(0deg);
                opacity: 0;
            }
        }

        /* Confetti Effect */
        .confetti-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .confetti {
            position: absolute;
            width: 15px;
            height: 15px;
            background: #ffd700;
            animation: fall 3s linear;
        }

        .confetti:nth-child(odd) {
            background: #ff0000;
            width: 10px;
            height: 10px;
            animation-duration: 2.5s;
        }

        .confetti:nth-child(3n) {
            background: #00ff00;
            width: 12px;
            height: 12px;
            animation-duration: 3.5s;
        }

        .confetti:nth-child(4n) {
            background: #00bfff;
            border-radius: 50%;
        }

        .confetti:nth-child(5n) {
            background: #ff00ff;
            width: 8px;
            height: 20px;
        }

        @keyframes fall {
            0% {
                transform: translateY(-100vh) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(720deg);
                opacity: 0;
            }
        }

        /* Sparkles Effect */
        .sparkle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: #fff;
            border-radius: 50%;
            animation: sparkle 1s ease-out;
        }

        @keyframes sparkle {
            0% {
                transform: translate(0, 0) scale(0);
                opacity: 1;
            }
            100% {
                transform: translate(var(--sx), var(--sy)) scale(1);
                opacity: 0;
            }
        }

        /* Lower Third for Events and Winner */
        .lower-third {
            position: fixed;
            bottom: 50px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            width: 90%;
            max-width: 800px;
            background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
            border-radius: 15px;
            padding: 20px 30px;
            display: none;
            z-index: 1000;
            border: 2px solid #ffd700;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            opacity: 0;
            transition: all 0.5s ease;
        }
        
        /* Professional Penalty Notification - Top Position */
        .penalty-notification {
            position: fixed;
            top: 120px; /* Below scoreboard */
            left: 50%;
            transform: translateX(-50%);
            display: none;
            z-index: 2000; /* Higher z-index than penalty counter */
            animation: slideInNotification 0.5s ease-out;
            max-width: 600px;
            width: 90%;
        }
        
        @keyframes slideInNotification {
            from {
                transform: translateX(-50%) translateY(-30px);
                opacity: 0;
            }
            to {
                transform: translateX(-50%) translateY(0);
                opacity: 1;
            }
        }
        
        /* Professional Notification Card */
        .notification-card {
            background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
            border-radius: 15px;
            padding: 20px 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.6), 
                        inset 0 1px 0 rgba(255,255,255,0.1);
            border: 1px solid rgba(255, 215, 0, 0.3);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }
        
        /* Gradient Accent Line */
        .notification-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10b981, #ffd700, #ef4444);
            animation: gradientMove 3s linear infinite;
        }
        
        @keyframes gradientMove {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        /* Notification Content */
        .notification-content {
            display: flex;
            align-items: center;
            gap: 20px;
            position: relative;
        }
        
        /* Penalty Result Icon */
        .penalty-result-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: bold;
            color: white;
            animation: iconPulse 0.8s ease;
        }
        
        .penalty-result-icon.goal {
            background: linear-gradient(135deg, #10b981, #059669);
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.5);
        }
        
        .penalty-result-icon.miss {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            box-shadow: 0 0 30px rgba(239, 68, 68, 0.5);
        }
        
        @keyframes iconPulse {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        
        /* Notification Text */
        .notification-text {
            flex: 1;
        }
        
        .notification-team {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 28px;
            color: #ffffff;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }
        
        .notification-result {
            font-size: 18px;
            color: #cbd5e1;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .notification-badge {
            background: rgba(255, 215, 0, 0.2);
            color: #ffd700;
            padding: 2px 10px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Minimal Score Display */
        .notification-score {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 32px;
            font-weight: 700;
            color: #ffd700;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .score-separator {
            color: #64748b;
            font-size: 24px;
        }
        
        /* Subtle Animation Effects */
        .penalty-notification.show {
            display: block;
        }
        
        /* Auto-hide animation */
        @keyframes fadeOutNotification {
            0% {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
            100% {
                opacity: 0;
                transform: translateX(-50%) translateY(-20px);
            }
        }
        
        .penalty-notification.hiding {
            animation: fadeOutNotification 0.5s ease-out forwards;
        }

        .lower-third.show {
            display: block;
            animation: slideInLowerThird 0.5s ease forwards;
        }

        @keyframes slideInLowerThird {
            0% {
                opacity: 0;
                transform: translateX(-50%) translateY(100px);
            }
            100% {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }

        /* Event Lower Third */
        .event-lower-third {
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: white;
        }

        .event-icon {
            width: 60px;
            height: 60px;
            background: #ffd700;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin-right: 20px;
            animation: pulse 1s ease infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .event-details {
            flex: 1;
        }

        .event-type {
            font-size: 14px;
            color: #ffd700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .event-player {
            font-size: 24px;
            font-weight: 700;
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }

        .event-team {
            font-size: 16px;
            color: #cbd5e1;
        }

        .event-minute {
            font-size: 36px;
            font-weight: 700;
            font-family: 'Bebas Neue', sans-serif;
            color: #ffd700;
            margin-left: 20px;
        }

        /* Winner Announcement */
        .winner-lower-third {
            text-align: center;
            color: white;
        }

        .winner-title {
            font-size: 20px;
            color: #ffd700;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
            font-family: 'Oswald', sans-serif;
        }

        .winner-team {
            font-size: 48px;
            font-weight: 900;
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: 3px;
            text-transform: uppercase;
            background: linear-gradient(45deg, #ffd700, #ff8c00);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: winnerGlow 2s ease infinite;
        }

        @keyframes winnerGlow {
            0%, 100% { 
                text-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
                transform: scale(1);
            }
            50% { 
                text-shadow: 0 0 40px rgba(255, 215, 0, 0.8);
                transform: scale(1.05);
            }
        }

        .winner-score {
            font-size: 24px;
            color: #cbd5e1;
            margin-top: 10px;
            font-family: 'Oswald', sans-serif;
        }

        /* Trophy Icon */
        .trophy-icon {
            font-size: 60px;
            color: #ffd700;
            margin-bottom: 10px;
            animation: bounce 1s ease infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .events-ticker {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 85%;
            max-width: 800px;
            height: 45px;
            background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
            border-radius: 25px;
            overflow: hidden;
            z-index: 999;
            display: none;
            border: 2px solid #ffd700;
            opacity: 0;
        }

        .events-ticker.show {
            display: block;
            animation: showHideTicker 8s ease-in-out;
        }

        @keyframes showHideTicker {
            0% {
                opacity: 0;
                transform: translateX(-50%) translateY(20px);
            }
            10% {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
            40% {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
            50% {
                opacity: 0;
                transform: translateX(-50%) translateY(20px);
            }
            60% {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
            90% {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
            100% {
                opacity: 0;
                transform: translateX(-50%) translateY(20px);
            }
        }

        .events-scroll {
            display: flex;
            align-items: center;
            height: 100%;
            padding: 0 20px;
            animation: scroll 20s linear infinite;
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
            color: #ffffff;
            font-size: clamp(12px, 1.8vw, 14px);
            font-weight: 500;
            white-space: nowrap;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .event-item::before {
            content: '⚽';
            font-size: 18px;
        }

        /* Watermark - Compact */
        .watermark {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: rgba(26, 26, 46, 0.8);
            color: white;
            padding: 10px 15px;
            border-radius: 10px;
            font-size: 11px;
            z-index: 1001;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #ffd700;
            backdrop-filter: blur(5px);
        }

        .watermark-logo {
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, #ffd700, #ff8c00);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 900;
            color: #1a1a2e;
            font-family: 'Bebas Neue', sans-serif;
        }

        .watermark-text {
            font-size: 10px;
            font-weight: 600;
            color: #ffd700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-family: 'Oswald', sans-serif;
        }

        /* Connection Status */
        .connection-status {
            position: fixed;
            top: 10px;
            left: 10px;
            width: 8px;
            height: 8px;
            background: #ff0000;
            border-radius: 50%;
            z-index: 1002;
            opacity: 0.7;
            transition: all 0.3s ease;
        }

        .connection-status.connected {
            background: #00ff00;
        }

        /* Hide Events Option */
        .events-ticker.hidden {
            display: none !important;
        }
        
        /* Penalty Counter Bottom Bar - Official Style */
        .penalty-counter {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #0a0a1a 0%, #1a1a2e 100%);
            border: 2px solid rgba(255, 215, 0, 0.3);
            border-radius: 20px;
            padding: 15px 30px;
            display: none;
            z-index: 1000;
            min-width: 500px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.8);
        }
        
        .penalty-counter.show {
            display: block;
            animation: slideUp 0.5s ease;
        }
        
        @keyframes slideUp {
            from {
                transform: translateX(-50%) translateY(100px);
                opacity: 0;
            }
            to {
                transform: translateX(-50%) translateY(0);
                opacity: 1;
            }
        }
        
        .penalty-counter-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
        }
        
        .penalty-team {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
        }
        
        .penalty-team-info {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }
        
        .penalty-team-name {
            font-family: 'Oswald', sans-serif;
            font-size: 12px;
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
        }
        
        .penalty-score {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 36px;
            font-weight: 700;
            padding: 5px 15px;
            border-radius: 15px;
            min-width: 50px;
            text-align: center;
            position: relative;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            background: linear-gradient(135deg, #ffd700 0%, #ff8c00 100%);
            color: #1a1a2e;
        }
        
        /* Penalty Indicators */
        .penalty-indicators {
            display: flex;
            align-items: center;
            gap: 5px;
            height: 24px;
        }
        
        .penalty-indicator {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: bold;
            color: white;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .penalty-indicator.goal {
            background: #10b981;
            box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);
        }
        
        .penalty-indicator.goal::after {
            content: '✓';
            font-size: 12px;
        }
        
        .penalty-indicator.miss {
            background: #ef4444;
            box-shadow: 0 0 10px rgba(239, 68, 68, 0.5);
        }
        
        .penalty-indicator.miss::after {
            content: '✗';
            font-size: 12px;
        }
        
        .penalty-indicator.pending {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Animation for new penalty */
        @keyframes penaltyPop {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .penalty-indicator.new {
            animation: penaltyPop 0.5s ease-out;
        }
        
        .penalty-center {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }
        
        .penalty-icon {
            font-size: 32px;
            animation: bounce 2s ease-in-out infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        
        .penalty-text {
            font-size: 12px;
            color: #ffd700;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 700;
            font-family: 'Bebas Neue', sans-serif;
        }
        
        /* Responsive Penalty Counter */
        @media (max-width: 768px) {
            .penalty-counter {
                min-width: 90%;
                padding: 10px 20px;
            }
            
            .penalty-score {
                font-size: 28px;
            }
            
            .penalty-indicators {
                gap: 3px;
            }
            
            .penalty-indicator {
                width: 16px;
                height: 16px;
            }
            
            /* Responsive Penalty Notification */
            .penalty-notification {
                top: 100px;
                width: 90%;
            }
            
            .notification-card {
                padding: 15px 25px;
            }
            
            .notification-team {
                font-size: 22px;
            }
            
            .notification-score {
                font-size: 24px;
            }
            
            .penalty-result-icon {
                width: 50px;
                height: 50px;
                font-size: 24px;
            }
            
            .notification-result {
                font-size: 16px;
            }
        }
        @media (max-width: 1024px) {
            .scoreboard {
                height: 70px;
                width: 92%;
            }
            
            .team-name {
                font-size: clamp(18px, 3.5vw, 28px);
            }
            
            .team-score {
                font-size: clamp(32px, 5vw, 42px);
            }
        }
        
        @media (max-width: 768px) {
            .scoreboard {
                height: 65px;
                width: 94%;
                min-width: 360px;
            }
            
            .team-section {
                padding: 0 12px;
            }
            
            .score-center {
                width: 80px;
            }
            
            .match-time {
                font-size: 18px;
            }
            
            .football {
                width: 60px;
                height: 60px;
            }
        }
        
        @media (max-width: 480px) {
            .scoreboard {
                height: 55px;
                width: 96%;
                min-width: 320px;
            }
            
            .team-name {
                font-size: clamp(14px, 4vw, 20px);
                letter-spacing: 0.5px;
            }
            
            .team-score {
                font-size: clamp(24px, 6vw, 32px);
                min-width: 35px;
            }
            
            .score-center {
                width: 65px;
                /* border-width: 2px; - REMOVED */
            }
            
            .match-time {
                font-size: 16px;
                letter-spacing: 0.5px;
            }
            
            .live-badge {
                font-size: 9px;
                padding: 3px 10px;
                top: -8px;
            }
            
            .live-icon {
                font-size: 8px;
                top: 5px;
            }
            
            .live-dot {
                width: 4px;
                height: 4px;
            }
            
            .events-ticker {
                height: 35px;
            }
            
            .event-item {
                font-size: 11px;
            }
        }
        
        /* Player List Modal - More Transparent */
        .player-list-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.9);
            width: 90%;
            max-width: 1200px;
            height: 90vh;
            max-height: 750px;
            background: rgba(10, 10, 26, 0.75);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 20px;
            border: 1px solid rgba(255, 215, 0, 0.15);
            box-shadow: 0 30px 90px rgba(0,0,0,0.6);
            display: none;
            z-index: 2000;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .player-list-modal.show {
            display: block;
            transform: translate(-50%, -50%) scale(1);
            animation: modalFadeIn 0.6s ease-out;
        }
        
        @keyframes modalFadeIn {
            0% {
                opacity: 0;
                transform: translate(-50%, -50%) scale(0.8) rotateX(15deg);
            }
            100% {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1) rotateX(0);
            }
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translate(-50%, -50%) scale(0.8) rotateX(20deg);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1) rotateX(0);
            }
        }
        
        /* Tournament Header with Wikipedia Logo */
        .tournament-header {
            background: rgba(26, 26, 46, 0.6);
            padding: 25px 40px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            border-bottom: 1px solid rgba(255, 215, 0, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        /* Wikipedia Logo */
        .wiki-logo {
            position: absolute;
            right: 30px;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            opacity: 0.6;
            transition: opacity 0.3s ease;
        }
        
        .wiki-logo:hover {
            opacity: 0.9;
        }
        
        .wiki-logo svg {
            width: 100%;
            height: 100%;
            fill: #ffd700;
        }
        
        .tournament-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.3), transparent);
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            100% { left: 100%; }
        }
        
        .tournament-icon {
            font-size: 36px;
            animation: rotateTrophy 3s ease-in-out infinite;
        }
        
        @keyframes rotateTrophy {
            0%, 100% { transform: rotate(-10deg); }
            50% { transform: rotate(10deg); }
        }
        
        .tournament-name {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 28px;
            color: #ffd700;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 3px;
            text-shadow: 0 2px 20px rgba(255, 215, 0, 0.5);
            animation: glow 2s ease-in-out infinite alternate;
        }
        
        @keyframes glow {
            from { text-shadow: 0 2px 20px rgba(255, 215, 0, 0.5); }
            to { text-shadow: 0 2px 30px rgba(255, 215, 0, 0.8), 0 0 40px rgba(255, 215, 0, 0.4); }
        }
        
        /* Player List Content - Traditional Lineup Layout */
        .player-list-content {
            display: flex;
            padding: 20px 30px 30px;
            gap: 60px;
            align-items: flex-start;
            justify-content: space-between;
            position: relative;
            min-height: 600px;
            height: calc(100% - 80px);
        }
        
        .player-list-team {
            flex: 1;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            padding: 0;
            backdrop-filter: blur(5px);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        .player-list-team::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .player-list-team:hover::before {
            opacity: 1;
        }
        
        .player-list-team.home-team {
            border: 1px solid rgba(0, 102, 204, 0.2);
            box-shadow: 0 0 20px rgba(0, 102, 204, 0.05);
        }
        
        .player-list-team.home-team:hover {
            border-color: rgba(0, 102, 204, 0.3);
            box-shadow: 0 0 30px rgba(0, 102, 204, 0.1);
        }
        
        .player-list-team.away-team {
            border: 1px solid rgba(220, 20, 60, 0.2);
            box-shadow: 0 0 20px rgba(220, 20, 60, 0.05);
        }
        
        .player-list-team.away-team:hover {
            border-color: rgba(220, 20, 60, 0.3);
            box-shadow: 0 0 30px rgba(220, 20, 60, 0.1);
        }
        
        .team-header {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 24px;
            text-align: center;
            margin: 0;
            padding: 15px 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            z-index: 1;
        }
        
        .player-list-team.home-team .team-header {
            background: linear-gradient(135deg, #0066cc 0%, #004499 100%);
            color: #fff;
        }
        
        .player-list-team.away-team .team-header {
            background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%);
            color: #fff;
        }
        
        /* Players List Layout - Optimized for 11 Players */
        .players-list {
            padding: 15px 20px;
            display: flex;
            flex-direction: column;
            gap: 0;
            height: calc(100% - 60px);
            min-height: 480px;
            overflow-y: auto;
            overflow-x: hidden;
        }
        
        /* Custom Scrollbar */
        .players-list::-webkit-scrollbar {
            width: 6px;
        }
        
        .players-list::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 3px;
        }
        
        .players-list::-webkit-scrollbar-thumb {
            background: rgba(255, 215, 0, 0.5);
            border-radius: 3px;
        }
        
        .players-list::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 215, 0, 0.7);
        }
        
        /* Player Item - Compact for 11 Players */
        .player-item {
            background: rgba(255, 255, 255, 0.04);
            border-radius: 6px;
            padding: 8px 16px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
            border: 1px solid transparent;
            position: relative;
            overflow: hidden;
            animation: playerItemSlide 0.4s ease-out backwards;
            min-height: 38px;
        }
        
        .player-item:nth-child(1) { animation-delay: 0.05s; }
        .player-item:nth-child(2) { animation-delay: 0.1s; }
        .player-item:nth-child(3) { animation-delay: 0.15s; }
        .player-item:nth-child(4) { animation-delay: 0.2s; }
        .player-item:nth-child(5) { animation-delay: 0.25s; }
        .player-item:nth-child(6) { animation-delay: 0.3s; }
        .player-item:nth-child(7) { animation-delay: 0.35s; }
        .player-item:nth-child(8) { animation-delay: 0.4s; }
        .player-item:nth-child(9) { animation-delay: 0.45s; }
        .player-item:nth-child(10) { animation-delay: 0.5s; }
        .player-item:nth-child(11) { animation-delay: 0.55s; }
        
        @keyframes playerItemSlide {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .player-list-team.away-team .player-item {
            animation-name: playerItemSlideRight;
        }
        
        @keyframes playerItemSlideRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .player-item:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 215, 0, 0.2);
            transform: translateX(5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        }
        
        .player-list-team.away-team .player-item:hover {
            transform: translateX(-5px);
        }
        
        .player-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, transparent, rgba(255, 215, 0, 0.1));
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .player-card:hover::before {
            opacity: 1;
        }
        
        .jersey-number {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 22px;
            color: #ffd700;
            font-weight: 700;
            line-height: 1;
            min-width: 32px;
            text-align: center;
            background: rgba(255, 215, 0, 0.08);
            padding: 4px 8px;
            border-radius: 6px;
            border: 1px solid rgba(255, 215, 0, 0.2);
        }
        
        /* Player Counter */
        .player-counter {
            position: absolute;
            bottom: 10px;
            right: 15px;
            font-size: 11px;
            color: rgba(255, 215, 0, 0.5);
            font-family: 'Oswald', sans-serif;
        }
        
        .player-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        
        .player-name {
            font-family: 'Oswald', sans-serif;
            font-size: 14px;
            color: #fff;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }
        
        .player-position {
            font-size: 11px;
            color: #cbd5e1;
            font-weight: 400;
            opacity: 0.7;
            line-height: 1;
        }
        
        /* VS Divider - Center Aligned */
        .vs-divider {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100px;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            flex-direction: column;
            gap: 15px;
            z-index: 10;
        }
        
        .vs-text {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 48px;
            color: #ffd700;
            font-weight: 900;
            text-shadow: 0 0 30px rgba(255, 215, 0, 0.6);
            animation: vsFloat 3s ease-in-out infinite;
        }
        
        .match-info {
            text-align: center;
        }
        
        .current-score {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 32px;
            color: #fff;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .match-status {
            font-size: 14px;
            color: #ffd700;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 4px 12px;
            background: rgba(255, 215, 0, 0.1);
            border-radius: 15px;
            border: 1px solid rgba(255, 215, 0, 0.3);
        }
        
        @keyframes vsFloat {
            0%, 100% { 
                transform: translateY(0) scale(1);
                text-shadow: 0 0 30px rgba(255, 215, 0, 0.6);
            }
            50% { 
                transform: translateY(-5px) scale(1.1);
                text-shadow: 0 5px 40px rgba(255, 215, 0, 0.8);
            }
        }
        
        .vs-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 120px;
            height: 120px;
            background: radial-gradient(circle, rgba(255, 215, 0, 0.1) 0%, transparent 70%);
            animation: vsPulse 3s ease-in-out infinite;
        }
        
        @keyframes vsPulse {
            0%, 100% { transform: translate(-50%, -50%) scale(0.8); opacity: 0.5; }
            50% { transform: translate(-50%, -50%) scale(1.2); opacity: 0.8; }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.1); opacity: 1; }
        }
        
        @keyframes modalFadeOut {
            from {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
            to {
                opacity: 0;
                transform: translate(-50%, -50%) scale(0.9);
            }
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            opacity: 0.4;
            transition: opacity 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 200px;
        }
        
        .empty-icon {
            font-size: 64px;
            margin-bottom: 15px;
            opacity: 0.3;
            filter: grayscale(100%);
            animation: pulse 3s ease-in-out infinite;
        }
        
        .empty-text {
            font-family: 'Hind Siliguri', sans-serif;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.5);
            font-weight: 400;
        }
        
        .player-list-team:hover .empty-state {
            opacity: 0.6;
        }
        
        @media (max-width: 768px) {
            .empty-state {
                padding: 40px 20px;
                min-height: 150px;
            }
            
            .empty-icon {
                font-size: 48px;
            }
            
            .empty-text {
                font-size: 14px;
            }
        }
        
        /* Responsive Player List */
        @media (max-width: 1024px) {
            .player-list-content {
                gap: 40px;
            }
            
            .player-item {
                padding: 10px 15px;
            }
        }
        
        @media (max-width: 768px) {
            .player-list-modal {
                width: 95%;
                max-height: 90vh;
            }
            
            .player-list-content {
                flex-direction: column;
                padding: 20px;
                gap: 20px;
            }
            
            .vs-divider {
                width: 100%;
                position: relative;
                left: auto;
                top: auto;
                transform: none;
                flex-direction: row;
                justify-content: space-around;
                padding: 15px;
                background: rgba(0, 0, 0, 0.2);
                border-radius: 10px;
            }
            
            .players-list {
                max-height: 200px;
            }
            
            .tournament-name {
                font-size: 20px;
            }
            
            .team-header {
                font-size: 20px;
                padding: 12px 15px;
            }
            
            .jersey-number {
                font-size: 24px;
                min-width: 35px;
                padding: 3px 8px;
            }
            
            .player-name {
                font-size: 14px;
            }
            
            .player-position {
                font-size: 11px;
            }
            
            .vs-text {
                font-size: 36px;
            }
            
            .current-score {
                font-size: 24px;
            }
        }
        
        /* Ultra Wide Screens */
        @media (min-width: 1920px) {
            .scoreboard {
                height: 85px;
                max-width: 1000px;
            }
            
            .team-name {
                font-size: 36px;
            }
            
            .team-score {
                font-size: 52px;
            }
            
            .match-time {
                font-size: 32px;
            }
        }
    </style>
</head>
<body>
    <!-- Connection Status Indicator -->
    <div class="connection-status" id="connectionStatus"></div>

    <!-- Goal Celebration Overlay -->
    <div class="goal-celebration" id="goalCelebration">
        <div class="football-container" id="footballContainer">
            <!-- Footballs will be added dynamically -->
        </div>
        <div class="fireworks" id="fireworks"></div>
        <div class="confetti-container" id="confettiContainer"></div>
        <div class="goal-text" id="goalText">GOAL!</div>
    </div>

    <!-- Main Scoreboard -->
    <div class="scoreboard" id="scoreboard">
        <!-- Live Badge -->
        @if($match->status === 'live')
        <div class="live-badge" id="liveBadge">
            <span>● LIVE</span>
        </div>
        @endif

        <!-- Home Team -->
        <div class="team-section home">
            <div class="team-name bangla-text" id="teamAName">{{ $match->team_a }}</div>
            <div class="team-score" id="teamAScore">{{ $match->team_a_score }}</div>
        </div>

        <!-- Center Score Display -->
        <div class="score-center">
            <div class="match-time" id="matchTimer">{{ sprintf('%02d:%02d', floor($match->match_time), ($match->match_time * 60) % 60) }}</div>
        </div>

        <!-- Away Team -->
        <div class="team-section away">
            <div class="team-name bangla-text" id="teamBName">{{ $match->team_b }}</div>
            <div class="team-score" id="teamBScore">{{ $match->team_b_score }}</div>
        </div>
    </div>

    <!-- Events Ticker - Hidden by default -->
    <div class="events-ticker {{ isset($showEventsTicker) && $showEventsTicker ? 'show' : 'hidden' }}" id="eventsTicker">
        <div class="events-scroll" id="eventsScroll">
            @if($events && count($events) > 0)
                @foreach($events as $event)
                @php
                    $eventMinute = is_array($event) ? ($event['minute'] ?? '0') : ($event->minute ?? '0');
                    $eventPlayer = is_array($event) ? ($event['player'] ?? 'Unknown') : ($event->player ?? 'Unknown');
                    $eventTeam = is_array($event) ? ($event['team'] ?? '') : ($event->team ?? '');
                @endphp
                <div class="event-item">
                    <span>{{ $eventMinute }}' {{ $eventPlayer }} ({{ $eventTeam }})</span>
                </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Lower Third Container -->
    <div class="lower-third" id="lowerThird">
        <!-- Content will be dynamically added -->
    </div>
    
    <!-- Professional Penalty Notification -->
    <div class="penalty-notification" id="penaltyNotification">
        <div class="notification-card">
            <div class="notification-content">
                <div class="penalty-result-icon" id="penaltyResultIcon">
                    <!-- Icon will be updated dynamically -->
                </div>
                <div class="notification-text">
                    <div class="notification-team" id="penaltyNotificationTeam"></div>
                    <div class="notification-result">
                        <span id="penaltyNotificationText"></span>
                        <span class="notification-badge" id="penaltyShooterBadge"></span>
                    </div>
                </div>
                <div class="notification-score">
                    <span id="penaltyScoreTeamA">0</span>
                    <span class="score-separator">-</span>
                    <span id="penaltyScoreTeamB">0</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Penalty Counter (Hidden by default, shows when penalty mode is on) -->
    <div class="penalty-counter" id="penaltyCounter">
        <div class="penalty-counter-content">
            <!-- Team A -->
            <div class="penalty-team">
                <div class="penalty-team-info">
                    <span class="penalty-team-name">{{ $match->team_a }}</span>
                    <span class="penalty-score" id="penaltyScoreA">0</span>
                </div>
                <div class="penalty-indicators" id="penaltyIndicatorsA">
                    <!-- Indicators will be added dynamically -->
                </div>
            </div>
            
            <!-- Center -->
            <div class="penalty-center">
                <div class="penalty-icon">⚽</div>
                <div class="penalty-text">PENALTIES</div>
            </div>
            
            <!-- Team B -->
            <div class="penalty-team">
                <div class="penalty-indicators" id="penaltyIndicatorsB">
                    <!-- Indicators will be added dynamically -->
                </div>
                <div class="penalty-team-info">
                    <span class="penalty-team-name">{{ $match->team_b }}</span>
                    <span class="penalty-score" id="penaltyScoreB">0</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Player List Modal -->
    <div class="player-list-modal" id="playerListModal">
        @if($match->tournament_name)
        <div class="tournament-header">
            <span class="tournament-icon">🏆</span>
            <span class="tournament-name">{{ $match->tournament_name }}</span>
            <!-- Wikipedia Logo -->
            <div class="wiki-logo">
                <svg viewBox="0 0 103 94" xmlns="http://www.w3.org/2000/svg">
                    <g>
                        <path d="M94.938,19H76.331l-1.332,0.229v1.788c0,0,4.025-0.372,5.212-0.372c1.159,0,1.717,0.23,1.717,1.402
                            c0,0.458-0.201,1.116-0.602,2.603L68.599,67.001L54.757,24.651c-0.401-1.202-0.688-2.317-0.688-2.775
                            c0-1.288,0.888-1.631,2.26-1.631c0.975,0,4.367,0.258,4.367,0.258v-1.731L61.9,19H38.245l0.006,1.731
                            c0,0,4.337-0.258,5.327-0.258c2.377,0,2.721,0.315,3.722,3.435l1.603,4.878l-12.7,36.865L22.131,24.536
                            c-0.517-1.345-0.917-2.805-0.917-3.292c0-1.115,0.401-1.401,1.288-1.401c1.116,0,5.518,0.343,5.518,0.343v-1.817L28.393,18
                            h-0.401H8.64v1.817c0,0,3.464-0.172,4.752-0.172c1.202,0,2.261,0.401,3.406,3.778l19.352,54.903h3.808l14.274-40.243
                            l13.789,40.214h3.722l19.065-53.159c1.317-3.664,1.946-4.838,3.722-5.037c1.231-0.144,5.298-0.315,5.298-0.315V18.026
                            L94.938,19z" fill="#ffd700"/>
                    </g>
                </svg>
            </div>
        </div>
        @endif
        
        <div class="player-list-content">
            <!-- Home Team List -->
            <div class="player-list-team home-team">
                <h3 class="team-header">{{ $match->team_a }}</h3>
                <div class="players-list">
                    @if(!$homePlayers || $homePlayers->isEmpty())
                        <div class="empty-state">
                            <div class="empty-icon">👥</div>
                            <p class="empty-text">কোনো প্লেয়ার যোগ করা হয়নি</p>
                        </div>
                    @else
                        @foreach($homePlayers as $player)
                        <div class="player-item">
                            <div class="jersey-number">{{ $player->jersey_number ?: '--' }}</div>
                            <div class="player-info">
                                <div class="player-name">{{ $player->name }}</div>
                                @if($player->position)
                                <div class="player-position">{{ $player->position }}</div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
                <div class="player-counter">{{ $homePlayers->count() }}/11</div>
            </div>
            
            <!-- VS Divider -->
            <div class="vs-divider">
                <div class="vs-text">VS</div>
                <div class="match-info">
                    <div class="current-score">{{ $match->team_a_score }} - {{ $match->team_b_score }}</div>
                    <div class="match-status">{{ strtoupper($match->status) }}</div>
                </div>
            </div>
            
            <!-- Away Team List -->
            <div class="player-list-team away-team">
                <h3 class="team-header">{{ $match->team_b }}</h3>
                <div class="players-list">
                    @if($awayPlayers->isEmpty())
                        <div class="empty-state">
                            <div class="empty-icon">👥</div>
                            <p class="empty-text">কোনো প্লেয়ার যোগ করা হয়নি</p>
                        </div>
                    @else
                        @foreach($awayPlayers as $player)
                        <div class="player-item">
                            <div class="jersey-number">{{ $player->jersey_number ?: '--' }}</div>
                            <div class="player-info">
                                <div class="player-name">{{ $player->name }}</div>
                                @if($player->position)
                                <div class="player-position">{{ $player->position }}</div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
                <div class="player-counter">{{ $awayPlayers->count() }}/11</div>
            </div>
        </div>
    </div>

    <!-- Watermark -->
    @if($showWatermark)
    <div class="watermark">
        <div class="watermark-logo">ROCKS</div>
        <div class="watermark-text">POWERD BY LONGON</div>
    </div>
    @endif

    @if(request()->has('debug'))
    <!-- Debug Mode -->
    <div style="position: fixed; bottom: 100px; left: 20px; background: rgba(0,0,0,0.8); color: white; padding: 20px; border-radius: 10px; font-size: 12px; z-index: 9999;">
        <h4>Debug Info</h4>
        <p>Match ID: {{ $match->id }}</p>
        <p>Team A: {{ $match->team_a }}</p>
        <p>Team B: {{ $match->team_b }}</p>
        <p>Home Players: {{ $homePlayers->count() }}</p>
        <p>Away Players: {{ $awayPlayers->count() }}</p>
        <p>Show Player List: {{ $match->show_player_list ? 'Yes' : 'No' }}</p>
        <hr style="margin: 10px 0;">
        <h5>Home Players:</h5>
        @foreach($homePlayers as $p)
            <p>- {{ $p->name }} (#{{ $p->jersey_number ?? 'N/A' }}) [{{ $p->pivot->team }}]</p>
        @endforeach
        <h5>Away Players:</h5>
        @foreach($awayPlayers as $p)
            <p>- {{ $p->name }} (#{{ $p->jersey_number ?? 'N/A' }}) [{{ $p->pivot->team }}]</p>
        @endforeach
    </div>
    @endif

    <!-- Include Pusher Script -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    
    <script>
        // Pass data from Laravel to JavaScript
        window.overlayToken = '{{ $overlayToken->token ?? "demo" }}';
        window.matchId = {{ $match->id ?? 1 }};
        window.showEventsTicker = {{ isset($showEventsTicker) && $showEventsTicker ? 'true' : 'false' }};
        window.matchData = {
            teamA: '{{ $match->team_a }}',
            teamB: '{{ $match->team_b }}',
            teamAScore: {{ $match->team_a_score }},
            teamBScore: {{ $match->team_b_score }},
            matchTimeMinutes: {{ floor($match->match_time) }},
            matchTimeSeconds: {{ ($match->match_time * 60) % 60 }},
            status: '{{ $match->status }}',
            events: {!! json_encode($events ?? []) !!},
            isTieBreaker: {{ $match->is_tie_breaker ? 'true' : 'false' }},
            tieBreakerData: {!! json_encode($match->tie_breaker_data ?? null) !!},
            penaltyShootoutEnabled: {{ $match->penalty_shootout_enabled ? 'true' : 'false' }},
            showPlayerList: {{ $match->show_player_list ? 'true' : 'false' }},
            tournamentName: '{{ $match->tournament_name }}'
        };

        // Configuration
        const matchToken = window.overlayToken;
        const matchId = window.matchId;
        let matchData = window.matchData;
        let pusher = null;
        let channel = null;
        let isConnected = false;
        let pollingInterval = null;

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

        // Enhanced Goal Celebration Functions
        function createFootballs() {
            const container = document.getElementById('footballContainer');
            container.innerHTML = '';
            
            // Create multiple footballs
            for (let i = 0; i < 3; i++) {
                const football = document.createElement('div');
                football.className = 'football';
                container.appendChild(football);
            }
        }

        function createFireworks() {
            const fireworks = document.getElementById('fireworks');
            fireworks.innerHTML = '';
            
            const colors = ['#ff0000', '#ffd700', '#00ff00', '#00bfff', '#ff00ff', '#ff8c00'];
            
            for (let i = 0; i < 50; i++) {
                setTimeout(() => {
                    const firework = document.createElement('div');
                    firework.className = 'firework';
                    firework.style.left = Math.random() * 100 + '%';
                    firework.style.top = Math.random() * 100 + '%';
                    firework.style.background = colors[Math.floor(Math.random() * colors.length)];
                    firework.style.setProperty('--x', (Math.random() - 0.5) * 300 + 'px');
                    firework.style.setProperty('--y', (Math.random() - 0.5) * 300 + 'px');
                    fireworks.appendChild(firework);
                    
                    // Add sparkles around firework
                    for (let j = 0; j < 5; j++) {
                        const sparkle = document.createElement('div');
                        sparkle.className = 'sparkle';
                        sparkle.style.left = firework.style.left;
                        sparkle.style.top = firework.style.top;
                        sparkle.style.setProperty('--sx', (Math.random() - 0.5) * 100 + 'px');
                        sparkle.style.setProperty('--sy', (Math.random() - 0.5) * 100 + 'px');
                        fireworks.appendChild(sparkle);
                    }
                    
                    setTimeout(() => {
                        firework.remove();
                    }, 1500);
                }, i * 50);
            }
        }

        function createConfetti() {
            const container = document.getElementById('confettiContainer');
            container.innerHTML = '';
            
            for (let i = 0; i < 150; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + '%';
                confetti.style.animationDelay = Math.random() * 3 + 's';
                confetti.style.transform = `rotate(${Math.random() * 360}deg)`;
                container.appendChild(confetti);
                
                setTimeout(() => confetti.remove(), 3500);
            }
        }

        function showGoalCelebration(team) {
            const celebration = document.getElementById('goalCelebration');
            const goalText = document.getElementById('goalText');
            
            celebration.classList.add('show');
            
            // Create all effects
            createFootballs();
            createFireworks();
            createConfetti();
            
            // Animate scoreboard
            document.getElementById('scoreboard').style.animation = 'pulse 0.5s ease 3';
            
            // Play sound effect (optional - if you have audio)
            // const audio = new Audio('/sounds/goal.mp3');
            // audio.play();
            
            setTimeout(() => {
                celebration.classList.remove('show');
                document.getElementById('scoreboard').style.animation = '';
            }, 2500);
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

        // Handle Player List Display
        function showPlayerList() {
            const modal = document.getElementById('playerListModal');
            const scoreboard = document.getElementById('scoreboard');
            
            modal.classList.add('show');
            
            // Hide header scoreboard
            scoreboard.style.opacity = '0';
            scoreboard.style.transform = 'translateX(-50%) translateY(-100px)';
            
            // Hide after 8 seconds with fade out
            setTimeout(() => {
                modal.style.animation = 'modalFadeOut 0.5s ease-out';
                setTimeout(() => {
                    modal.classList.remove('show');
                    modal.style.animation = '';
                    
                    // Show header scoreboard again
                    scoreboard.style.opacity = '1';
                    scoreboard.style.transform = 'translateX(-50%) translateY(0)';
                }, 500);
            }, 8000);
        }
        
        function hidePlayerList() {
            const modal = document.getElementById('playerListModal');
            modal.classList.remove('show');
        }
        
        // Update Display Function
        function updateDisplay(data) {
            // Flash scoreboard on update (but not for settings changes)
            if (data.updateType !== 'settings') {
                const scoreboard = document.getElementById('scoreboard');
                scoreboard.classList.add('update-flash');
                setTimeout(() => {
                    scoreboard.classList.remove('update-flash');
                }, 500);
            }

            document.getElementById('teamAName').textContent = data.teamA;
            document.getElementById('teamBName').textContent = data.teamB;
            
            // Check for goals (only if it's a score update)
            if (data.updateType === 'goal_team_a' || (data.updateType === 'score' && data.teamAScore > previousScores.teamA)) {
                showGoalCelebration('teamA');
                document.getElementById('teamAScore').classList.add('goal-scored');
                setTimeout(() => {
                    document.getElementById('teamAScore').classList.remove('goal-scored');
                }, 1000);
            }
            if (data.updateType === 'goal_team_b' || (data.updateType === 'score' && data.teamBScore > previousScores.teamB)) {
                showGoalCelebration('teamB');
                document.getElementById('teamBScore').classList.add('goal-scored');
                setTimeout(() => {
                    document.getElementById('teamBScore').classList.remove('goal-scored');
                }, 1000);
            }
            
            // Update scores
            document.getElementById('teamAScore').textContent = data.teamAScore;
            document.getElementById('teamBScore').textContent = data.teamBScore;
            
            previousScores.teamA = data.teamAScore;
            previousScores.teamB = data.teamBScore;
            
            // Update timer
            if (data.matchTimeMinutes !== undefined && data.matchTimeSeconds !== undefined) {
                setTimer(data.matchTimeMinutes, data.matchTimeSeconds);
            }
            
            // Update live indicator
            updateLiveIndicator(data.status);
            
            // Update penalty counter if in tie-breaker mode
            updatePenaltyCounter(data);
            
            // Handle player list display
            if (data.showPlayerList !== undefined) {
                if (data.showPlayerList) {
                    showPlayerList();
                } else {
                    hidePlayerList();
                }
            }
            
            // Check for winner (only if status changed to finished)
            if (data.updateType === 'status' && data.status === 'finished') {
                checkForWinner(data);
            }
            
            // Update events ticker if enabled
            if (window.showEventsTicker && data.events) {
                updateEventsTicker(data.events);
            }
        }
        
        // Penalty Counter Functions
        function updatePenaltyCounter(data) {
            console.log('updatePenaltyCounter called with data:', data);
            const penaltyCounter = document.getElementById('penaltyCounter');
            
            // Simple check - if penalty mode is enabled, show the counter
            if (data.penaltyShootoutEnabled) {
                penaltyCounter.classList.add('show');
                
                // Update penalty scores and indicators if tie-breaker data exists
                if (data.tieBreakerData && data.tieBreakerData.team_a && data.tieBreakerData.team_b) {
                    console.log('Updating penalty scores:', data.tieBreakerData.team_a.goals, data.tieBreakerData.team_b.goals);
                    
                    // Update scores
                    document.getElementById('penaltyScoreA').textContent = data.tieBreakerData.team_a.goals || 0;
                    document.getElementById('penaltyScoreB').textContent = data.tieBreakerData.team_b.goals || 0;
                    
                    // Update indicators
                    updatePenaltyIndicators('A', data.tieBreakerData.team_a.attempts || []);
                    updatePenaltyIndicators('B', data.tieBreakerData.team_b.attempts || []);
                } else {
                    console.log('No tie-breaker data found');
                    // Show 0-0 if no tie-breaker data yet
                    document.getElementById('penaltyScoreA').textContent = '0';
                    document.getElementById('penaltyScoreB').textContent = '0';
                    // Clear indicators
                    document.getElementById('penaltyIndicatorsA').innerHTML = '';
                    document.getElementById('penaltyIndicatorsB').innerHTML = '';
                }
            } else {
                console.log('Penalty mode is disabled');
                // Hide if penalty mode is off
                penaltyCounter.classList.remove('show');
            }
        }
        
        // Update penalty indicators
        function updatePenaltyIndicators(team, attempts) {
            const container = document.getElementById(`penaltyIndicators${team}`);
            container.innerHTML = '';
            
            // Add indicators for taken penalties
            attempts.forEach((scored, index) => {
                const indicator = document.createElement('div');
                indicator.className = `penalty-indicator ${scored ? 'goal' : 'miss'}`;
                container.appendChild(indicator);
            });
            
            // Add pending indicators (up to 5 total for first round)
            const remaining = Math.max(0, 5 - attempts.length);
            for (let i = 0; i < remaining; i++) {
                const indicator = document.createElement('div');
                indicator.className = 'penalty-indicator pending';
                container.appendChild(indicator);
            }
            
            // If we have the latest penalty, animate it and show notification
            if (attempts.length > 0) {
                const lastIndicator = container.children[attempts.length - 1];
                lastIndicator.classList.add('new');
                setTimeout(() => {
                    lastIndicator.classList.remove('new');
                }, 500);
                
                // Show penalty notification
                const lastAttempt = attempts[attempts.length - 1];
                const teamName = team === 'A' ? matchData.teamA : matchData.teamB;
                const shooterNumber = attempts.length;
                
                // Get current penalty scores
                const teamAScore = document.getElementById('penaltyScoreA').textContent || '0';
                const teamBScore = document.getElementById('penaltyScoreB').textContent || '0';
                
                showPenaltyNotification(teamName, lastAttempt, teamAScore, teamBScore, shooterNumber);
            }
        }
        
        // Function to show penalty notification
        function showPenaltyNotification(teamName, scored, teamAScore, teamBScore, shooterNumber) {
            const notification = document.getElementById('penaltyNotification');
            const icon = document.getElementById('penaltyResultIcon');
            const team = document.getElementById('penaltyNotificationTeam');
            const text = document.getElementById('penaltyNotificationText');
            const badge = document.getElementById('penaltyShooterBadge');
            const scoreA = document.getElementById('penaltyScoreTeamA');
            const scoreB = document.getElementById('penaltyScoreTeamB');
            
            // Update content
            team.textContent = teamName;
            scoreA.textContent = teamAScore;
            scoreB.textContent = teamBScore;
            badge.textContent = `Shooter #${shooterNumber}`;
            
            if (scored) {
                icon.className = 'penalty-result-icon goal';
                icon.innerHTML = '✓';
                text.textContent = 'Penalty Goal!';
                
                // Add mini celebration for penalty goal
                const scoreboard = document.getElementById('scoreboard');
                scoreboard.style.animation = 'pulse 0.5s ease 2';
                setTimeout(() => {
                    scoreboard.style.animation = '';
                }, 1000);
            } else {
                icon.className = 'penalty-result-icon miss';
                icon.innerHTML = '✗';
                text.textContent = 'Penalty Missed!';
            }
            
            // Show notification
            notification.classList.add('show');
            notification.classList.remove('hiding');
            
            // Auto-hide after 3 seconds
            setTimeout(() => {
                notification.classList.add('hiding');
                setTimeout(() => {
                    notification.classList.remove('show');
                    notification.classList.remove('hiding');
                }, 500);
            }, 3000);
        }

        function updateLiveIndicator(status) {
            const liveBadge = document.getElementById('liveBadge');
            
            if (status === 'live') {
                // Add badge if not exists
                if (!liveBadge) {
                    const badge = document.createElement('div');
                    badge.className = 'live-badge';
                    badge.id = 'liveBadge';
                    badge.innerHTML = '<span>● LIVE</span>';
                    document.getElementById('scoreboard').appendChild(badge);
                }
                
                timer.isRunning = true;
            } else {
                if (liveBadge) liveBadge.remove();
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
                const eventMinute = event.minute || '0';
                const eventPlayer = event.player || 'Unknown';
                const eventTeam = event.team || '';
                eventItem.innerHTML = `<span>${eventMinute}' ${eventPlayer} (${eventTeam})</span>`;
                scroll.appendChild(eventItem);
            });
            
            if (recentEvents.length > 0) {
                ticker.classList.remove('hidden');
                ticker.classList.add('show');
            }
        }

        // Update connection status
        function updateConnectionStatus(connected) {
            const status = document.getElementById('connectionStatus');
            if (connected) {
                status.classList.add('connected');
                isConnected = true;
            } else {
                status.classList.remove('connected');
                isConnected = false;
            }
        }

        // Initialize Pusher
        function initializePusher() {
            try {
                pusher = new Pusher('8ccf5d4c4bf78fcec3c9', {
                    cluster: 'ap2',
                    encrypted: true,
                    forceTLS: true
                });

                channel = pusher.subscribe('match.' + matchId);

                channel.bind('match-updated', function(data) {
                    console.log('Match updated via Pusher:', data);
                    if (data.match) {
                        // Merge with existing data to preserve all fields
                        matchData = { ...matchData, ...data.match };
                        // Add updateType from the broadcast
                        matchData.updateType = data.updateType;
                        updateDisplay(matchData);
                        
                        // Special handling for penalty updates
                        if (data.updateType === 'penalty' && data.match.tieBreakerData) {
                            console.log('Penalty update received:', data.match.tieBreakerData);
                            // Force update penalty counter
                            updatePenaltyCounter(data.match);
                        }
                    }
                });

                channel.bind('event-added', function(data) {
                    console.log('Event added via Pusher:', data);
                    if (data.event) {
                        // Add to events list
                        if (matchData.events) {
                            matchData.events.push(data.event);
                            if (window.showEventsTicker) {
                                updateEventsTicker(matchData.events);
                            }
                        }
                        // Show lower third
                        showEventLowerThird(data.event);
                    }
                });

                pusher.connection.bind('connected', function() {
                    console.log('Pusher connected');
                    updateConnectionStatus(true);
                });

                pusher.connection.bind('disconnected', function() {
                    console.log('Pusher disconnected');
                    updateConnectionStatus(false);
                });

                pusher.connection.bind('error', function(err) {
                    console.error('Pusher error:', err);
                    updateConnectionStatus(false);
                });

            } catch (error) {
                console.error('Failed to initialize Pusher:', error);
                updateConnectionStatus(false);
            }
        }

        // Toggle events ticker
        function toggleEventsTicker() {
            const ticker = document.getElementById('eventsTicker');
            ticker.classList.toggle('hidden');
            ticker.classList.toggle('show');
            window.showEventsTicker = !window.showEventsTicker;
        }

        // Fallback polling mechanism
        function startPolling() {
            pollingInterval = setInterval(async () => {
                if (!isConnected) {
                    await fetchMatchData();
                }
            }, 3000);
        }

        // Real-time data fetching (fallback)
        async function fetchMatchData() {
            try {
                const response = await fetch(`/api/overlay-data/${matchId}`);
                if (response.ok) {
                    const data = await response.json();
                    if (data.success && data.match) {
                        console.log('Fetched match data:', data.match);
                        matchData = { ...matchData, ...data.match };
                        updateDisplay(matchData);
                        // Force update penalty counter
                        updatePenaltyCounter(matchData);
                    }
                }
            } catch (error) {
                console.error('Error fetching match data:', error);
            }
        }

        // Initialize overlay
        function initializeOverlay() {
            console.log('Initial match data:', matchData);
            console.log('Show player list:', matchData.showPlayerList);
            
            updateDisplay(matchData);
            updateTimerDisplay();
            startTimer();
            
            // Initialize penalty counter
            updatePenaltyCounter(matchData);
            
            // Check if player list should be shown on load
            if (matchData.showPlayerList) {
                console.log('Showing player list on load');
                showPlayerList();
            }
            
            // Initialize Pusher for real-time updates
            initializePusher();
            
            // Start polling as fallback
            startPolling();
        }

        // Start when page loads
        document.addEventListener('DOMContentLoaded', initializeOverlay);

        // Lower Third Functions
        function showEventLowerThird(event) {
            const lowerThird = document.getElementById('lowerThird');
            
            // Check if penalty mode is active - if so, don't show lower third for any event
            if (matchData.penaltyShootoutEnabled && matchData.tieBreakerData && matchData.tieBreakerData.isActive) {
                console.log('Penalty mode active - skipping lower third');
                return;
            }
            
            // Check if it's a penalty event from the penalty counter
            if ((event.event_type === 'penalty' || event.type === 'penalty') && 
                event.player && 
                (event.player.includes('GOAL') || event.player.includes('MISS'))) {
                // Don't show lower third for penalty events - they have their own notification
                return;
            }
            
            // Check if it's a winner announcement
            if (event.event_type === 'winner_announcement' || event.type === 'winner_announcement') {
                // Only show if there's actual winner info
                if (event.player && event.player !== 'DRAW') {
                    // Extract winner info
                    const winnerTeam = event.player || 'Unknown';
                    const finalScore = event.description || '';
                    showWinnerAnnouncement(winnerTeam, finalScore.replace('Final Score: ', ''));
                }
                return;
            }
            
            // Get event icon
            let icon = '⚽';
            switch(event.type || event.event_type) {
                case 'goal':
                    icon = '⚽';
                    break;
                case 'yellow_card':
                    icon = '🟨';
                    break;
                case 'red_card':
                    icon = '🟥';
                    break;
                case 'substitution':
                    icon = '🔄';
                    break;
                case 'penalty':
                    icon = '⚠️';
                    break;
            }
            
            // Create event content
            lowerThird.innerHTML = `
                <div class="event-lower-third">
                    <div class="event-icon">${icon}</div>
                    <div class="event-details">
                        <div class="event-type">${(event.type || event.event_type || '').replace('_', ' ').toUpperCase()}</div>
                        <div class="event-player">${event.player || 'Unknown Player'}</div>
                        <div class="event-team">${event.team_name || event.team || ''}</div>
                    </div>
                    <div class="event-minute">${event.minute}'</div>
                </div>
            `;
            
            // Show lower third
            lowerThird.classList.add('show');
            
            // Hide after 5 seconds
            setTimeout(() => {
                lowerThird.classList.remove('show');
            }, 5000);
        }
        
        function showWinnerAnnouncement(winnerTeam, finalScore) {
            const lowerThird = document.getElementById('lowerThird');
            
            // Create winner content
            lowerThird.innerHTML = `
                <div class="winner-lower-third">
                    <div class="trophy-icon">🏆</div>
                    <div class="winner-title">MATCH WINNER</div>
                    <div class="winner-team">${winnerTeam}</div>
                    <div class="winner-score">Final Score: ${finalScore}</div>
                </div>
            `;
            
            // Show lower third
            lowerThird.classList.add('show');
            
            // Play goal celebration too
            showGoalCelebration('winner');
            
            // Keep showing for 10 seconds
            setTimeout(() => {
                lowerThird.classList.remove('show');
            }, 10000);
        }
        
        // Listen for winner announcement
        function checkForWinner(data) {
            // Only check if match just finished and scores are different
            if (data.status === 'finished') {
                const scoreA = parseInt(data.teamAScore);
                const scoreB = parseInt(data.teamBScore);
                
                // Only announce winner if scores are different (not a draw)
                if (scoreA !== scoreB) {
                    let winnerTeam = '';
                    let finalScore = `${scoreA} - ${scoreB}`;
                    
                    if (scoreA > scoreB) {
                        winnerTeam = data.teamA;
                    } else {
                        winnerTeam = data.teamB;
                    }
                    
                    // Show winner announcement
                    showWinnerAnnouncement(winnerTeam, finalScore);
                }
                // For draws, don't auto-announce (wait for penalty result)
            }
        }

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            if (pusher) {
                pusher.disconnect();
            }
            if (pollingInterval) {
                clearInterval(pollingInterval);
            }
            if (timer.interval) {
                clearInterval(timer.interval);
            }
        });
    </script>
</body>
</html>
