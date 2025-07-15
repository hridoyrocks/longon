{{-- resources/views/admin/users.blade.php - Modern Admin Users Management --}}
<x-app-layout>
    <div class="min-h-screen">
        <!-- Header Section -->
        <div class="bg-white/80 backdrop-blur-sm border-b border-white/20 sticky top-16 z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent bangla-text">
                            ব্যবহারকারী ব্যবস্থাপনা
                        </h1>
                        <p class="text-slate-600 mt-1 bangla-text">সব ব্যবহারকারী দেখুন এবং পরিচালনা করুন</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="bg-white/60 backdrop-blur-sm rounded-full px-4 py-2 border border-white/20">
                            <span class="text-sm text-slate-600 bangla-text">মোট: {{ $users->total() }} জন</span>
                        </div>
                        <button class="btn-primary">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span class="bangla-text">নতুন ব্যবহারকারী</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Filter Section -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-white/20 p-6 mb-6">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-slate-700 bangla-text">ব্যবহারকারীর ধরন:</label>
                        <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">সব ধরন</option>
                            <option value="user">সাধারণ ব্যবহারকারী</option>
                            <option value="reseller">রিসেলার</option>
                            <option value="admin">অ্যাডমিন</option>
                        </select>
                    </div>
                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-slate-700 bangla-text">স্ট্যাটাস:</label>
                        <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">সব স্ট্যাটাস</option>
                            <option value="active">সক্রিয়</option>
                            <option value="inactive">নিষ্ক্রিয়</option>
                        </select>
                    </div>
                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-slate-700 bangla-text">সার্চ:</label>
                        <input type="text" placeholder="নাম বা ইমেইল..." class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <button class="btn-primary text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span class="bangla-text">খুঁজুন</span>
                    </button>
                </div>
            </div>

            <!-- Users Table -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-white/20 overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-slate-900 bangla-text">ব্যবহারকারী তালিকা</h2>
                        <div class="flex space-x-2">
                            <button class="btn-success text-sm" onclick="showBulkActionModal()">
                                <span class="bangla-text">বাল্ক কর্ম</span>
                            </button>
                            <button class="btn-secondary text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="bangla-text">এক্সপোর্ট</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    <input type="checkbox" id="select_all" class="rounded border-gray-300">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bangla-text">ব্যবহারকারী</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bangla-text">ধরন</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bangla-text">ক্রেডিট</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bangla-text">মোট খরচ</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bangla-text">যোগদান</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bangla-text">শেষ কার্যকলাপ</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bangla-text">কর্ম</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="user-checkbox rounded border-gray-300">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                                                <span class="text-white font-semibold text-lg">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            @if($user->phone)
                                            <div class="text-xs text-gray-400">{{ $user->phone }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @if($user->user_type === 'admin') bg-red-100 text-red-800
                                        @elseif($user->user_type === 'reseller') bg-purple-100 text-purple-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        @if($user->user_type === 'admin') 🔴 অ্যাডমিন
                                        @elseif($user->user_type === 'reseller') 🟣 রিসেলার
                                        @else 🔵 ব্যবহারকারী
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="text-sm font-bold text-gray-900">{{ $user->credits_balance }}</span>
                                        <button onclick="showCreditModal({{ $user->id }}, '{{ $user->name }}', {{ $user->credits_balance }})" 
                                                class="ml-2 text-xs text-blue-600 hover:text-blue-800 bangla-text">সম্পাদনা</button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ৳{{ number_format($user->total_spent ?? 0) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div>{{ $user->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $user->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($user->last_activity)
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                        <span>{{ $user->last_activity->diffForHumans() }}</span>
                                    </div>
                                    @else
                                    <span class="text-gray-400 bangla-text">কোনো কার্যকলাপ নেই</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <button onclick="viewUser({{ $user->id }})" class="text-blue-600 hover:text-blue-900 bangla-text">দেখুন</button>
                                        <button onclick="editUser({{ $user->id }})" class="text-green-600 hover:text-green-900 bangla-text">সম্পাদনা</button>
                                        @if($user->user_type !== 'admin')
                                        <button onclick="deleteUser({{ $user->id }})" class="text-red-600 hover:text-red-900 bangla-text">মুছুন</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-slate-500 font-medium bangla-text">কোনো ব্যবহারকারী পাওয়া যায়নি</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Credit Adjustment Modal -->
    <div id="creditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 bangla-text">ক্রেডিট সমন্বয়</h3>
                <div class="mt-2 text-sm text-gray-500">
                    <p><strong>ব্যবহারকারী:</strong> <span id="creditUserName"></span></p>
                    <p><strong>বর্তমান ব্যালেন্স:</strong> <span id="creditCurrentBalance"></span></p>
                </div>
                <form id="creditForm" method="POST" class="mt-4">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 bangla-text">কর্মের ধরন:</label>
                        <select name="type" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="add">যোগ করুন</option>
                            <option value="subtract">বিয়োগ করুন</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 bangla-text">ক্রেডিট পরিমাণ:</label>
                        <input type="number" name="credits" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" min="0" step="0.01" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 bangla-text">কারণ:</label>
                        <textarea name="reason" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required></textarea>
                    </div>
                    <div class="flex items-center justify-end space-x-2">
                        <button type="button" onclick="hideCreditModal()" class="btn-secondary">বাতিল</button>
                        <button type="submit" class="btn-primary">সম্পাদনা করুন</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function showCreditModal(userId, userName, currentBalance) {
            document.getElementById('creditUserName').textContent = userName;
            document.getElementById('creditCurrentBalance').textContent = currentBalance;
            document.getElementById('creditForm').action = `/admin/users/${userId}/adjust-credits`;
            document.getElementById('creditModal').classList.remove('hidden');
        }

        function hideCreditModal() {
            document.getElementById('creditModal').classList.add('hidden');
        }

        function viewUser(userId) {
            // Implement view user functionality
            window.location.href = `/admin/users/${userId}`;
        }

        function editUser(userId) {
            // Implement edit user functionality
            window.location.href = `/admin/users/${userId}/edit`;
        }

        function deleteUser(userId) {
            if (confirm('আপনি কি নিশ্চিত যে এই ব্যবহারকারীকে মুছে ফেলতে চান?')) {
                fetch(`/admin/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('ব্যবহারকারী মুছতে সমস্যা হয়েছে');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('ব্যবহারকারী মুছতে সমস্যা হয়েছে');
                });
            }
        }

        // Select all functionality
        document.getElementById('select_all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        function showBulkActionModal() {
            const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
            if (checkedBoxes.length === 0) {
                alert('অনুগ্রহ করে কমপক্ষে একটি ব্যবহারকারী নির্বাচন করুন');
                return;
            }
            
            const action = prompt('কি করতে চান?\n1. activate (সক্রিয় করুন)\n2. deactivate (নিষ্ক্রিয় করুন)\n3. send_notification (বিজ্ঞপ্তি পাঠান)');
            
            if (action) {
                // Implement bulk action
                console.log('Bulk action:', action, 'for', checkedBoxes.length, 'users');
            }
        }
    </script>
</x-app-layout>