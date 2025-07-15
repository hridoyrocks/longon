<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">অ্যাডমিন ড্যাশবোর্ড</h1>
            <p class="text-gray-600">সিস্টেম পরিচালনা প্যানেল</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="card bg-blue-500 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">মোট ব্যবহারকারী</p>
                        <p class="text-2xl font-bold">{{ $stats['total_users'] }}</p>
                    </div>
                    <div class="text-3xl">👥</div>
                </div>
            </div>

            <div class="card bg-yellow-500 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">অপেক্ষমান পেমেন্ট</p>
                        <p class="text-2xl font-bold">{{ $stats['pending_payments'] }}</p>
                    </div>
                    <div class="text-3xl">⏳</div>
                </div>
            </div>

            <div class="card bg-green-500 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">মোট ম্যাচ</p>
                        <p class="text-2xl font-bold">{{ $stats['total_matches'] }}</p>
                    </div>
                    <div class="text-3xl">⚽</div>
                </div>
            </div>

            <div class="card bg-purple-500 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">মোট আয়</p>
                        <p class="text-2xl font-bold">৳{{ number_format($stats['total_revenue']) }}</p>
                    </div>
                    <div class="text-3xl">💰</div>
                </div>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="card">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold">অপেক্ষমান পেমেন্ট</h2>
                <a href="{{ route('admin.payments') }}" class="text-blue-600 hover:text-blue-800">সব দেখুন</a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ব্যবহারকারী</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">পরিমাণ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">পেমেন্ট মাধ্যম</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">তারিখ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">কর্ম</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentPayments as $payment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $payment->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $payment->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ৳{{ number_format($payment->amount) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst($payment->payment_method) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $payment->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <form method="POST" action="{{ route('admin.payments.approve', $payment->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900">অনুমোদন</button>
                                    </form>
                                    <button onclick="showRejectModal({{ $payment->id }})" class="text-red-600 hover:text-red-900">প্রত্যাখ্যান</button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">কোনো অপেক্ষমান পেমেন্ট নেই</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>