<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">নতুন ম্যাচ তৈরি করুন</h1>
            <p class="text-gray-600">একটি নতুন ফুটবল ম্যাচ তৈরি করুন এবং লাইভ স্কোর ট্র্যাক করুন</p>
        </div>

        <!-- Credit Warning -->
        @if(auth()->user()->credits_balance < 1)
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <p class="font-semibold">⚠️ পর্যাপ্ত ক্রেডিট নেই!</p>
            <p>একটি ম্যাচ তৈরি করতে আপনার ১টি ক্রেডিট প্রয়োজন। আপনার বর্তমান ব্যালেন্স: {{ auth()->user()->credits_balance }}</p>
            <a href="{{ route('credits.purchase') }}" class="btn-primary mt-2">ক্রেডিট কিনুন</a>
        </div>
        @endif

        <!-- Credit Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <div class="text-blue-500 mr-3">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-blue-800 font-medium">ক্রেডিট তথ্য</p>
                    <p class="text-blue-700 text-sm">আপনার বর্তমান ক্রেডিট ব্যালেন্স: <span class="font-semibold">{{ auth()->user()->credits_balance }}</span></p>
                    <p class="text-blue-700 text-sm">প্রতিটি ম্যাচ তৈরি করতে ১টি ক্রেডিট খরচ হবে</p>
                </div>
            </div>
        </div>

        <!-- Match Create Form -->
        <div class="card">
            <form method="POST" action="{{ route('matches.store') }}">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Team A -->
                    <div>
                        <label for="team_a" class="block text-sm font-medium text-gray-700 mb-2">
                            প্রথম দল
                        </label>
                        <input 
                            type="text" 
                            id="team_a" 
                            name="team_a" 
                            value="{{ old('team_a') }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="যেমন: বাংলাদেশ"
                            required
                        >
                        @error('team_a')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Team B -->
                    <div>
                        <label for="team_b" class="block text-sm font-medium text-gray-700 mb-2">
                            দ্বিতীয় দল
                        </label>
                        <input 
                            type="text" 
                            id="team_b" 
                            name="team_b" 
                            value="{{ old('team_b') }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="যেমন: ভারত"
                            required
                        >
                        @error('team_b')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Match Preview -->
                <div class="mt-6 bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold mb-2">ম্যাচ পূর্বরূপ</h3>
                    <div class="flex items-center justify-center space-x-4 text-lg">
                        <span id="preview_team_a" class="font-bold text-blue-600">প্রথম দল</span>
                        <span class="text-gray-500">VS</span>
                        <span id="preview_team_b" class="font-bold text-red-600">দ্বিতীয় দল</span>
                    </div>
                </div>

                <!-- Match Features -->
                <div class="mt-6 bg-green-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-green-800 mb-2">এই ম্যাচে যা পাবেন:</h3>
                    <ul class="space-y-2 text-green-700">
                        <li class="flex items-center">
                            <span class="text-green-500 mr-2">✓</span>
                            রিয়েল-টাইম স্কোর আপডেট
                        </li>
                        <li class="flex items-center">
                            <span class="text-green-500 mr-2">✓</span>
                            ম্যাচ ইভেন্ট ট্র্যাকিং (গোল, কার্ড, প্রতিস্থাপন)
                        </li>
                        <li class="flex items-center">
                            <span class="text-green-500 mr-2">✓</span>
                            লাইভ স্ট্রিমিং ওভারলে
                        </li>
                        <li class="flex items-center">
                            <span class="text-green-500 mr-2">✓</span>
                            ম্যাচ রিপোর্ট এবং পরিসংখ্যান
                        </li>
                    </ul>
                </div>

                <!-- Submit Button -->
                <div class="mt-6 flex items-center justify-between">
                    <a href="{{ route('dashboard') }}" class="btn-secondary">
                        বাতিল
                    </a>
                    
                    <button 
                        type="submit" 
                        class="btn-primary"
                        @if(auth()->user()->credits_balance < 1) disabled @endif
                    >
                        @if(auth()->user()->credits_balance < 1)
                            পর্যাপ্ত ক্রেডিট নেই
                        @else
                            ম্যাচ তৈরি করুন (১ ক্রেডিট)
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Live preview update
        document.getElementById('team_a').addEventListener('input', function() {
            const preview = document.getElementById('preview_team_a');
            preview.textContent = this.value || 'প্রথম দল';
        });

        document.getElementById('team_b').addEventListener('input', function() {
            const preview = document.getElementById('preview_team_b');
            preview.textContent = this.value || 'দ্বিতীয় দল';
        });
    </script>
</x-app-layout>