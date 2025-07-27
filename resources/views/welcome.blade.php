<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Football Scoreboard - Professional Live Score Management</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .hero-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        
        .slide-in {
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-xl">⚽</span>
                        </div>
                        <span class="text-xl font-bold text-gray-900">Football Scoreboard</span>
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition">
                                Login
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-2 rounded-lg font-medium hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                                    Get Started
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-white hero-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="slide-in">
                    <h1 class="text-5xl lg:text-6xl font-black text-gray-900 leading-tight">
                        Professional <span class="gradient-text">Live Score</span> Management
                    </h1>
                    <p class="mt-6 text-xl text-gray-600 leading-relaxed">
                        Create stunning live scoreboards for your football matches. Perfect for tournaments, leagues, and live streaming with OBS integration.
                    </p>
                    
                    <div class="mt-8 flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-xl hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                            Start Free Trial
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                        <a href="#features" class="inline-flex items-center justify-center px-8 py-4 bg-gray-100 text-gray-900 font-bold rounded-xl hover:bg-gray-200 transition-all duration-300">
                            Learn More
                        </a>
                    </div>
                    
                    <div class="mt-12 flex items-center space-x-8">
                        <div>
                            <div class="text-3xl font-bold text-gray-900">1000+</div>
                            <div class="text-gray-600">Active Users</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-gray-900">50K+</div>
                            <div class="text-gray-600">Matches Created</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-gray-900">4.9★</div>
                            <div class="text-gray-600">User Rating</div>
                        </div>
                    </div>
                </div>
                
                <div class="relative">
                    <div class="floating">
                        <img src="https://images.unsplash.com/photo-1508098682722-e99c43a406b2?w=600&h=400&fit=crop" alt="Football Stadium" class="rounded-2xl shadow-2xl">
                        
                        <!-- Floating Score Card -->
                        <div class="absolute -top-4 -right-4 bg-white rounded-xl shadow-xl p-4 transform rotate-3">
                            <div class="flex items-center space-x-4">
                                <div class="text-center">
                                    <div class="text-sm font-medium text-gray-600">Team A</div>
                                    <div class="text-2xl font-bold text-gray-900">3</div>
                                </div>
                                <div class="text-xl font-bold text-gray-400">-</div>
                                <div class="text-center">
                                    <div class="text-sm font-medium text-gray-600">Team B</div>
                                    <div class="text-2xl font-bold text-gray-900">2</div>
                                </div>
                            </div>
                            <div class="mt-2 text-center text-sm text-green-600 font-medium">● LIVE</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black text-gray-900">Everything You Need</h2>
                <p class="mt-4 text-xl text-gray-600">Powerful features for professional live score management</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">OBS Integration</h3>
                    <p class="text-gray-600">Seamless integration with OBS Studio for professional live streaming</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Real-time Updates</h3>
                    <p class="text-gray-600">Instant score updates that sync across all connected devices</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Custom Themes</h3>
                    <p class="text-gray-600">Beautiful scoreboard themes to match your brand</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Match Events</h3>
                    <p class="text-gray-600">Track goals, cards, substitutions, and more with timestamps</p>
                </div>
                
                <!-- Feature 5 -->
                <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Tournament Mode</h3>
                    <p class="text-gray-600">Manage multiple matches and tournaments with ease</p>
                </div>
                
                <!-- Feature 6 -->
                <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Analytics</h3>
                    <p class="text-gray-600">Track viewer engagement and match statistics</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black text-gray-900">How It Works</h2>
                <p class="mt-4 text-xl text-gray-600">Get started in just 3 simple steps</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-3xl font-bold">
                        1
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Create Match</h3>
                    <p class="text-gray-600">Enter team names and match details to create your scoreboard</p>
                </div>
                
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-3xl font-bold">
                        2
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Get Overlay Link</h3>
                    <p class="text-gray-600">Copy the unique overlay URL for your OBS browser source</p>
                </div>
                
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-3xl font-bold">
                        3
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Control Live</h3>
                    <p class="text-gray-600">Update scores and events in real-time from control panel</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-black text-white mb-6">
                Ready to Stream Like a Pro?
            </h2>
            <p class="text-xl text-indigo-100 mb-8">
                Join thousands of streamers who trust our platform for their live scores
            </p>
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-indigo-600 font-bold rounded-xl hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                Get Started Free
                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-xl">⚽</span>
                        </div>
                        <span class="text-xl font-bold text-white">Football Scoreboard</span>
                    </div>
                    <p class="text-sm">Professional live score management for football tournaments and leagues.</p>
                </div>
                
                <div>
                    <h4 class="font-bold text-white mb-4">Product</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#features" class="hover:text-white transition">Features</a></li>
                        <li><a href="#" class="hover:text-white transition">Pricing</a></li>
                        <li><a href="#" class="hover:text-white transition">API</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold text-white mb-4">Support</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">Documentation</a></li>
                        <li><a href="#" class="hover:text-white transition">Tutorials</a></li>
                        <li><a href="#" class="hover:text-white transition">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold text-white mb-4">Legal</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white transition">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm">
                <p>&copy; 2025 Football Scoreboard. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
