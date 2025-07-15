{{-- resources/views/reseller/dashboard.blade.php --}}
<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">রিসেলার ড্যাশবোর্ড</h1>
            <p class="text-gray-600">আপনার বিক্রয় পরিসংখ্যান এবং কমিশন ট্র্যাক করুন</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <div class="card bg-blue-500 text-white">
                <div class="text-center">
                    <div class="text-3xl font-bold">{{ $stats['total_customers'] }}</div>
                    <div class="text-sm opacity-90">মোট কাস্টমার</div>
                </div>
            </div>

            <div class="card bg-green-500 text-white">
                <div class="text-center">
                    <div class="text-3xl font-bold">{{ $stats['active_customers'] }}</div>
                    <div class="text-sm opacity-90">সক্রিয় কাস্টমার</div>
                </div>
            </div>

            <div class="card bg-purple-500 text-white">
                <div class="text-center">
                    <div class="text-3xl font-bold">৳{{ number_format($stats['monthly_sales']) }}</div>
                    <div class="text-sm opacity-90">এই মাসের বিক্রয়</div>
                </div>
            </div>

            <div class="card bg-orange-500 text-white">
                <div class="text-center">
                    <div class="text-3xl font-bold">৳{{ number_format($stats['commission_balance']) }}</div>
                    <div class="text-sm opacity-90">কমিশন ব্যালেন্স</div>
                </div>
            </div>

            <div class="card bg-red-500 text-white">
                <div class="text-center">
                    <div class="text-3xl font-bold">৳{{ number_format($stats['total_commission_earned']) }}</div>
                    <div class="text-sm opacity-90">মোট কমিশন আয়</div>
                </div>
            </div>
        </div>

        <!-- Current Tier & Referral Link -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="card">
                <h2 class="text-xl font-semibold mb-4">বর্তমান টায়ার</h2>
                @if($currentTier)
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-4 rounded-lg">
                    <h3 class="text-lg font-bold">{{ $currentTier->name }}</h3>
                    <p class="text-sm opacity-90">Level 1: {{ $currentTier->level_1_commission }}% | Level 2: {{ $currentTier->level_2_commission }}%</p>
                    <p class="text-sm opacity-90">লক্ষ্য: ৳{{ number_format($currentTier->max_monthly_sales ?? 0) }}</p>
                </div>
                @else
                <p class="text-gray-500">কোনো টায়ার নির্ধারিত নেই</p>
                @endif
            </div>

            <div class="card">
                <h2 class="text-xl font-semibold mb-4">রেফারেল লিংক</h2>
                <div class="flex">
                    <input type="text" id="referral_link" class="flex-1 border rounded-l-md px-3 py-2 text-sm" readonly>
                    <button onclick="generateReferralLink()" class="bg-blue-500 text-white px-4 py-2 rounded-r-md">তৈরি করুন</button>
                </div>
                <button onclick="copyReferralLink()" class="mt-2 w-full bg-green-500 text-white px-4 py-2 rounded">কপি করুন</button>
            </div>
        </div>

        <!-- Recent Commissions -->
        <div class="card">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold">সাম্প্রতিক কমিশন</h2>
                <a href="{{ route('reseller.commissions') }}" class="text-blue-600 hover:text-blue-800">সব দেখুন</a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">কাস্টমার</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">পরিমাণ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">কমিশন</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">স্ট্যাটাস</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">তারিখ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentCommissions as $commission)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $commission->customer->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">৳{{ number_format($commission->purchase_amount) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-green-600">৳{{ number_format($commission->commission_amount) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($commission->status === 'paid') bg-green-100 text-green-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($commission->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $commission->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">কোনো কমিশন নেই</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payout Request -->
        <div class="mt-8">
            <div class="card">
                <h2 class="text-xl font-semibold mb-4">পেআউট রিকোয়েস্ট</h2>
                <form method="POST" action="{{ route('reseller.request-payout') }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">পরিমাণ</label>
                            <input type="number" name="amount" class="mt-1 block w-full border rounded-md px-3 py-2" 
                                   min="100" max="{{ $stats['commission_balance'] }}" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">পেমেন্ট মাধ্যম</label>
                            <select name="payment_method" class="mt-1 block w-full border rounded-md px-3 py-2" required>
                                <option value="bkash">bKash</option>
                                <option value="nagad">Nagad</option>
                                <option value="rocket">Rocket</option>
                                <option value="bank">Bank Transfer</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">অ্যাকাউন্ট নম্বর</label>
                            <input type="text" name="account_number" class="mt-1 block w-full border rounded-md px-3 py-2" required>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn-primary">পেআউট রিকোয়েস্ট করুন</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function generateReferralLink() {
            fetch('/reseller/generate-referral-link', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('referral_link').value = data.referral_link;
            });
        }

        function copyReferralLink() {
            const linkInput = document.getElementById('referral_link');
            if (linkInput.value) {
                linkInput.select();
                document.execCommand('copy');
                alert('রেফারেল লিংক কপি হয়েছে!');
            } else {
                alert('প্রথমে রেফারেল লিংক তৈরি করুন!');
            }
        }
    </script>
</x-app-layout>