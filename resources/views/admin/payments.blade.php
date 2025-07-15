{{-- resources/views/admin/payments.blade.php --}}
<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">পেমেন্ট ম্যানেজমেন্ট</h1>
            <p class="text-gray-600">সব পেমেন্ট রিকোয়েস্ট দেখুন এবং পরিচালনা করুন</p>
        </div>

        <!-- Filter Options -->
        <div class="card mb-6">
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">স্ট্যাটাস:</label>
                    <select class="border border-gray-300 rounded-md px-3 py-1 text-sm">
                        <option value="">সব স্ট্যাটাস</option>
                        <option value="pending">অপেক্ষমান</option>
                        <option value="approved">অনুমোদিত</option>
                        <option value="rejected">প্রত্যাখ্যাত</option>
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">পেমেন্ট মাধ্যম:</label>
                    <select class="border border-gray-300 rounded-md px-3 py-1 text-sm">
                        <option value="">সব মাধ্যম</option>
                        <option value="bkash">bKash</option>
                        <option value="nagad">Nagad</option>
                        <option value="rocket">Rocket</option>
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">সার্চ:</label>
                    <input type="text" placeholder="ব্যবহারকারীর নাম..." class="border border-gray-300 rounded-md px-3 py-1 text-sm">
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="card">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold">পেমেন্ট রিকোয়েস্ট</h2>
                <div class="flex space-x-2">
                    <button class="btn-success text-sm" onclick="showBulkApproveModal()">বাল্ক অনুমোদন</button>
                    <button class="btn-danger text-sm" onclick="showBulkRejectModal()">বাল্ক প্রত্যাখ্যান</button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" id="select_all" class="rounded border-gray-300">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ব্যবহারকারী</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">পরিমাণ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">পেমেন্ট মাধ্যম</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaction ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">স্ট্যাটাস</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">তারিখ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">কর্ম</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($payments as $payment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" name="payment_ids[]" value="{{ $payment->id }}" class="payment-checkbox rounded border-gray-300">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                        {{ strtoupper(substr($payment->user->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $payment->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $payment->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">৳{{ number_format($payment->amount) }}</div>
                                @if($payment->package)
                                <div class="text-xs text-gray-500">{{ $payment->package->name }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ strtoupper($payment->payment_method) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $payment->transaction_id }}</div>
                                @if($payment->payment_screenshot)
                                <a href="{{ asset('storage/' . $payment->payment_screenshot) }}" target="_blank" class="text-xs text-blue-600 hover:underline">স্ক্রিনশট দেখুন</a>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($payment->status === 'approved') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    @if($payment->status === 'pending') অপেক্ষমান
                                    @elseif($payment->status === 'approved') অনুমোদিত
                                    @else প্রত্যাখ্যাত @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $payment->created_at->format('M d, Y') }}</div>
                                <div class="text-xs">{{ $payment->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($payment->status === 'pending')
                                <div class="flex space-x-2">
                                    <form method="POST" action="{{ route('admin.payments.approve', $payment->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900 text-sm">অনুমোদন</button>
                                    </form>
                                    <button onclick="showRejectModal({{ $payment->id }})" class="text-red-600 hover:text-red-900 text-sm">প্রত্যাখ্যান</button>
                                </div>
                                @else
                                <span class="text-gray-400 text-sm">প্রক্রিয়া সম্পন্ন</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">কোনো পেমেন্ট রিকোয়েস্ট নেই</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $payments->links() }}
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg font-medium text-gray-900">পেমেন্ট প্রত্যাখ্যান</h3>
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">প্রত্যাখ্যানের কারণ:</label>
                        <textarea name="admin_notes" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2" required></textarea>
                    </div>
                    <div class="items-center px-4 py-3 flex space-x-2">
                        <button type="submit" class="btn-danger flex-1">প্রত্যাখ্যান করুন</button>
                        <button type="button" onclick="hideRejectModal()" class="btn-secondary flex-1">বাতিল</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showRejectModal(paymentId) {
            document.getElementById('rejectForm').action = `/admin/payments/${paymentId}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function hideRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        function showBulkApproveModal() {
            const checkedBoxes = document.querySelectorAll('.payment-checkbox:checked');
            if (checkedBoxes.length === 0) {
                alert('অনুগ্রহ করে কমপক্ষে একটি পেমেন্ট নির্বাচন করুন');
                return;
            }
            
            if (confirm(`আপনি কি নিশ্চিত যে ${checkedBoxes.length}টি পেমেন্ট অনুমোদন করতে চান?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.bulk-actions") }}';
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.innerHTML = `
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="action" value="approve_payments">
                `;
                
                checkedBoxes.forEach(checkbox => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = checkbox.value;
                    form.appendChild(input);
                });
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        function showBulkRejectModal() {
            const checkedBoxes = document.querySelectorAll('.payment-checkbox:checked');
            if (checkedBoxes.length === 0) {
                alert('অনুগ্রহ করে কমপক্ষে একটি পেমেন্ট নির্বাচন করুন');
                return;
            }
            
            const reason = prompt(`${checkedBoxes.length}টি পেমেন্ট প্রত্যাখ্যানের কারণ লিখুন:`);
            if (reason) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.bulk-actions") }}';
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.innerHTML = `
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="action" value="reject_payments">
                    <input type="hidden" name="reason" value="${reason}">
                `;
                
                checkedBoxes.forEach(checkbox => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = checkbox.value;
                    form.appendChild(input);
                });
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Select all functionality
        document.getElementById('select_all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.payment-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    </script>
</x-app-layout>