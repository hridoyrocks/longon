<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">ক্রেডিট কিনুন</h1>
            <p class="text-gray-600">আপনার বর্তমান ব্যালেন্স: <span class="font-semibold text-green-600">{{ auth()->user()->credits_balance }} ক্রেডিট</span></p>
        </div>

        <!-- Payment Instructions -->
        <div class="card mb-8 bg-blue-50 border-blue-200">
            <h2 class="text-xl font-semibold text-blue-900 mb-4">পেমেন্ট নির্দেশনা</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center">
                    <div class="text-3xl mb-2">📱</div>
                    <h3 class="font-semibold text-blue-900">bKash</h3>
                    <p class="text-sm text-blue-700">01XXXXXXXXX</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl mb-2">💳</div>
                    <h3 class="font-semibold text-blue-900">Nagad</h3>
                    <p class="text-sm text-blue-700">01XXXXXXXXX</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl mb-2">🚀</div>
                    <h3 class="font-semibold text-blue-900">Rocket</h3>
                    <p class="text-sm text-blue-700">01XXXXXXXXX</p>
                </div>
            </div>
            <div class="mt-4 text-sm text-blue-700">
                <p><strong>ধাপসমূহ:</strong></p>
                <ol class="list-decimal list-inside space-y-1">
                    <li>একটি প্যাকেজ নির্বাচন করুন</li>
                    <li>উপরের যেকোনো নম্বরে পেমেন্ট করুন</li>
                    <li>Transaction ID এবং Screenshot আপলোড করুন</li>
                    <li>২৪ ঘন্টার মধ্যে ক্রেডিট যোগ হবে</li>
                </ol>
            </div>
        </div>

        <!-- Packages -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @php
            $packages = [
                ['name' => 'Starter', 'credits' => 5, 'price' => 1000, 'discount' => 0],
                ['name' => 'Standard', 'credits' => 10, 'price' => 2000, 'discount' => 10],
                ['name' => 'Premium', 'credits' => 20, 'price' => 4000, 'discount' => 15],
                ['name' => 'Business', 'credits' => 50, 'price' => 10000, 'discount' => 20],
                ['name' => 'Enterprise', 'credits' => 100, 'price' => 20000, 'discount' => 25]
            ];
            @endphp

            @foreach($packages as $index => $package)
            <div class="card hover:shadow-lg transition-shadow border-2 {{ $index === 1 ? 'border-blue-500' : 'border-gray-200' }}">
                @if($index === 1)
                <div class="bg-blue-500 text-white text-center py-1 text-sm font-medium">জনপ্রিয়</div>
                @endif
                
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $package['name'] }}</h3>
                    <div class="text-center mb-4">
                        <div class="text-3xl font-bold text-gray-900">{{ $package['credits'] }}</div>
                        <div class="text-sm text-gray-600">ক্রেডিট</div>
                    </div>
                    
                    <div class="text-center mb-4">
                        @if($package['discount'] > 0)
                        <div class="text-sm text-gray-500 line-through">৳{{ $package['price'] }}</div>
                        <div class="text-2xl font-bold text-green-600">৳{{ $package['price'] * (1 - $package['discount']/100) }}</div>
                        <div class="text-sm text-green-600">{{ $package['discount'] }}% ছাড়</div>
                        @else
                        <div class="text-2xl font-bold text-gray-900">৳{{ $package['price'] }}</div>
                        @endif
                    </div>
                    
                    <div class="text-center mb-4">
                        <div class="text-sm text-gray-600">প্রতি ক্রেডিট: ৳{{ number_format($package['price'] * (1 - $package['discount']/100) / $package['credits'], 2) }}</div>
                    </div>
                    
                    <button onclick="selectPackage({{ $index }}, '{{ $package['name'] }}', {{ $package['credits'] }}, {{ $package['price'] * (1 - $package['discount']/100) }})" 
                            class="w-full btn-primary">
                        নির্বাচন করুন
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Payment Form -->
        <div class="card" id="payment_form" style="display: none;">
            <h2 class="text-xl font-semibold mb-4">পেমেন্ট তথ্য</h2>
            
            <form method="POST" action="{{ route('credits.submit-payment') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="package_id" id="selected_package_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">নির্বাচিত প্যাকেজ</label>
                        <div id="selected_package_info" class="p-3 bg-gray-50 rounded"></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পেমেন্ট মাধ্যম</label>
                        <select name="payment_method" class="w-full border rounded-md px-3 py-2" required>
                            <option value="">নির্বাচন করুন</option>
                            <option value="bkash">bKash</option>
                            <option value="nagad">Nagad</option>
                            <option value="rocket">Rocket</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Transaction ID</label>
                        <input type="text" name="transaction_id" class="w-full border rounded-md px-3 py-2" 
                               placeholder="XXXXXXXXXX" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">পেমেন্ট স্ক্রিনশট</label>
                        <input type="file" name="payment_screenshot" class="w-full border rounded-md px-3 py-2" 
                               accept="image/*" required>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-between">
                    <button type="button" onclick="hidePaymentForm()" class="btn-secondary">বাতিল</button>
                    <button type="submit" class="btn-primary">পেমেন্ট সাবমিট করুন</button>
                </div>
            </form>
        </div>

        <!-- FAQ -->
        <div class="card mt-8">
            <h2 class="text-xl font-semibold mb-4">সাধারণ প্রশ্ন</h2>
            <div class="space-y-4">
                <div>
                    <h3 class="font-medium">১ ক্রেডিট = কত টাকা?</h3>
                    <p class="text-sm text-gray-600">সাধারণত ১ ক্রেডিট = ২০০ টাকা, তবে প্যাকেজ অনুযায়ী ছাড় পাবেন।</p>
                </div>
                <div>
                    <h3 class="font-medium">পেমেন্ট কতক্ষণে অনুমোদিত হবে?</h3>
                    <p class="text-sm text-gray-600">সাধারণত ১-২৪ ঘন্টার মধ্যে। জরুরি প্রয়োজনে সাপোর্টে যোগাযোগ করুন।</p>
                </div>
                <div>
                    <h3 class="font-medium">ক্রেডিট কি expire হয়?</h3>
                    <p class="text-sm text-gray-600">না, ক্রেডিট কখনো expire হয় না। যতক্ষণ চান ব্যবহার করতে পারেন।</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectPackage(index, name, credits, price) {
            document.getElementById('selected_package_id').value = index;
            document.getElementById('selected_package_info').innerHTML = `
                <div class="font-semibold">${name}</div>
                <div class="text-sm text-gray-600">${credits} ক্রেডিট</div>
                <div class="text-lg font-bold text-green-600">৳${price}</div>
            `;
            
            document.getElementById('payment_form').style.display = 'block';
            document.getElementById('payment_form').scrollIntoView({ behavior: 'smooth' });
        }
        
        function hidePaymentForm() {
            document.getElementById('payment_form').style.display = 'none';
        }
    </script>
</x-app-layout>