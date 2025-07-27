<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">ক্রেডিট কিনুন</h1>
            <p class="text-gray-600">আপনার বর্তমান ব্যালেন্স: <span class="font-semibold text-green-600">{{ auth()->user()->credits_balance }} ক্রেডিট</span></p>
        </div>

        <!-- Payment Instructions -->
        <div class="card mb-8 bg-blue-50 border-blue-200">
            <h2 class="text-xl font-semibold text-blue-900 mb-4">পেমেন্ট নির্দেশনা</h2>
            <div class="mt-4 text-sm text-blue-700">
                <p><strong>ধাপসমূহ:</strong></p>
                <ol class="list-decimal list-inside space-y-1">
                    <li>একটি প্যাকেজ নির্বাচন করুন</li>
                    <li>পেমেন্ট মাধ্যম নির্বাচন করুন</li>
                    <li>প্রদর্শিত নম্বরে পেমেন্ট করুন</li>
                    <li>Transaction ID এবং Screenshot আপলোড করুন</li>
                    <li>২৪ ঘন্টার মধ্যে ক্রেডিট যোগ হবে</li>
                </ol>
            </div>
        </div>

        <!-- Packages -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @forelse($packages as $index => $package)
            <div class="card hover:shadow-lg transition-shadow border-2 {{ $package->is_popular ? 'border-blue-500' : 'border-gray-200' }}">
                @if($package->is_popular)
                <div class="bg-blue-500 text-white text-center py-1 text-sm font-medium">জনপ্রিয়</div>
                @endif
                
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $package->name }}</h3>
                    <div class="text-center mb-4">
                        <div class="text-3xl font-bold text-gray-900">{{ $package->credits }}</div>
                        <div class="text-sm text-gray-600">ক্রেডিট</div>
                    </div>
                    
                    <div class="text-center mb-4">
                        @if($package->discount_percentage > 0)
                        <div class="text-sm text-gray-500 line-through">৳{{ number_format($package->price) }}</div>
                        <div class="text-2xl font-bold text-green-600">৳{{ number_format($package->discounted_price) }}</div>
                        <div class="text-sm text-green-600">{{ $package->discount_percentage }}% ছাড়</div>
                        @else
                        <div class="text-2xl font-bold text-gray-900">৳{{ number_format($package->price) }}</div>
                        @endif
                    </div>
                    
                    <div class="text-center mb-4">
                        <div class="text-sm text-gray-600">প্রতি ক্রেডিট: ৳{{ number_format($package->discounted_price / $package->credits, 2) }}</div>
                    </div>
                    
                    <button onclick="selectPackage({{ $package->id }}, '{{ $package->name }}', {{ $package->credits }}, {{ $package->discounted_price }})" 
                            class="w-full btn-primary">
                        নির্বাচন করুন
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-8">
                <p class="text-gray-500">কোন প্যাকেজ পাওয়া যায়নি</p>
            </div>
            @endforelse
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
                        <select name="payment_method" id="payment_method" class="w-full border rounded-md px-3 py-2" required onchange="showPaymentNumber()">
                            <option value="">নির্বাচন করুন</option>
                            <option value="bkash">bKash</option>
                            <option value="nagad">Nagad</option>
                            <option value="rocket">Rocket</option>
                        </select>
                        
                        <!-- Payment Number Display -->
                        <div id="payment_number_display" class="mt-3 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg border border-indigo-200" style="display: none;">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">পেমেন্ট নম্বর:</p>
                                    <p class="text-2xl font-bold text-indigo-600" id="payment_number"></p>
                                    <p class="text-xs text-gray-500 mt-1" id="payment_type"></p>
                                </div>
                                <button type="button" onclick="copyPaymentNumber()" class="p-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                    <svg id="copy_icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    <svg id="check_icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
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
        // Payment numbers configuration
        const paymentNumbers = {
            bkash: {
                number: '01753477957',
                type: 'bKash Personal'
            },
            nagad: {
                number: '01753477957',
                type: 'Nagad Personal'
            },
            rocket: {
                number: '01753477957',
                type: 'Rocket Personal'
            }
        };

        function selectPackage(packageId, name, credits, price) {
            document.getElementById('selected_package_id').value = packageId;
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
            document.getElementById('payment_method').value = '';
            document.getElementById('payment_number_display').style.display = 'none';
        }
        
        function showPaymentNumber() {
            const method = document.getElementById('payment_method').value;
            const display = document.getElementById('payment_number_display');
            
            if (method && paymentNumbers[method]) {
                document.getElementById('payment_number').textContent = paymentNumbers[method].number;
                document.getElementById('payment_type').textContent = paymentNumbers[method].type;
                display.style.display = 'block';
                
                // Animate appearance
                display.style.opacity = '0';
                display.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    display.style.transition = 'all 0.3s ease';
                    display.style.opacity = '1';
                    display.style.transform = 'translateY(0)';
                }, 10);
            } else {
                display.style.display = 'none';
            }
        }
        
        function copyPaymentNumber() {
            const number = document.getElementById('payment_number').textContent;
            navigator.clipboard.writeText(number).then(() => {
                // Show success feedback
                document.getElementById('copy_icon').classList.add('hidden');
                document.getElementById('check_icon').classList.remove('hidden');
                
                // Show toast notification
                showToast('নম্বর কপি হয়েছে!');
                
                // Reset icon after 2 seconds
                setTimeout(() => {
                    document.getElementById('copy_icon').classList.remove('hidden');
                    document.getElementById('check_icon').classList.add('hidden');
                }, 2000);
            });
        }
        
        function showToast(message) {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300';
            toast.textContent = message;
            toast.style.opacity = '0';
            toast.style.transform = 'translate(-50%, 20px)';
            
            document.body.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translate(-50%, 0)';
            }, 10);
            
            // Remove after 3 seconds
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translate(-50%, 20px)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
        
        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    
                    // Check if all required fields are filled
                    const packageId = document.getElementById('selected_package_id').value;
                    const paymentMethod = form.querySelector('select[name="payment_method"]').value;
                    const transactionId = form.querySelector('input[name="transaction_id"]').value;
                    const screenshot = form.querySelector('input[name="payment_screenshot"]').files[0];
                    
                    if (!packageId || !paymentMethod || !transactionId || !screenshot) {
                        e.preventDefault();
                        alert('সব ফিল্ড পূরণ করুন');
                        return false;
                    }
                    
                    // Check file size (2MB max)
                    if (screenshot.size > 2 * 1024 * 1024) {
                        e.preventDefault();
                        alert('ফাইল সাইজ ২MB এর বেশি হতে পারবে না');
                        return false;
                    }
                    
                    // Check file type
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                    if (!allowedTypes.includes(screenshot.type)) {
                        e.preventDefault();
                        alert('শুধুমাত্র JPG, JPEG, PNG ফাইল অনুমোদিত');
                        return false;
                    }
                    
                    // Disable submit button to prevent double submission
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = 'সাবমিট হচ্ছে...';
                });
            }
        });
    </script>
</x-app-layout>