{{-- resources/views/admin/edit-user.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit User') }}
            </h2>
            <a href="{{ route('admin.users.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Back to Users
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="border-b pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div>
                                    <x-input-label for="name" :value="__('Name')" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" 
                                                  :value="old('name', $user->name)" required autofocus />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                <!-- Email -->
                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" 
                                                  :value="old('email', $user->email)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                </div>

                                <!-- Password (Optional) -->
                                <div>
                                    <x-input-label for="password" :value="__('New Password (leave blank to keep current)')" />
                                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
                                    <x-input-error class="mt-2" :messages="$errors->get('password')" />
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
                                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" 
                                                  class="mt-1 block w-full" />
                                </div>
                            </div>
                        </div>

                        <!-- Account Settings -->
                        <div class="border-b pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Account Settings</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- User Type -->
                                <div>
                                    <x-input-label for="user_type" :value="__('User Type')" />
                                    <select id="user_type" name="user_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="user" {{ $user->user_type == 'user' ? 'selected' : '' }}>Regular User</option>
                                        <option value="reseller" {{ $user->user_type == 'reseller' ? 'selected' : '' }}>Reseller</option>
                                        <option value="admin" {{ $user->user_type == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('user_type')" />
                                </div>

                                <!-- Active Status -->
                                <div>
                                    <x-input-label for="is_active" :value="__('Account Status')" />
                                    <select id="is_active" name="is_active" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="1" {{ $user->is_active ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('is_active')" />
                                </div>
                            </div>
                        </div>

                        <!-- Reseller Information -->
                        <div id="reseller-section" class="border-b pb-6" style="{{ $user->user_type == 'reseller' ? '' : 'display: none;' }}">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Reseller Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Business Name -->
                                <div>
                                    <x-input-label for="business_name" :value="__('Business Name')" />
                                    <x-text-input id="business_name" name="business_name" type="text" 
                                                  class="mt-1 block w-full" :value="old('business_name', $user->business_name)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('business_name')" />
                                </div>

                                <!-- Monthly Sales Target -->
                                <div>
                                    <x-input-label for="monthly_sales_target" :value="__('Monthly Sales Target (৳)')" />
                                    <x-text-input id="monthly_sales_target" name="monthly_sales_target" type="number" 
                                                  class="mt-1 block w-full" :value="old('monthly_sales_target', $user->monthly_sales_target)" min="0" />
                                    <x-input-error class="mt-2" :messages="$errors->get('monthly_sales_target')" />
                                </div>
                            </div>
                        </div>

                        <!-- Account Statistics (Read-only) -->
                        <div class="border-b pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Account Statistics</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Current Credits</label>
                                    <p class="mt-1 text-2xl font-bold text-gray-900">{{ $user->credits_balance }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Total Spent</label>
                                    <p class="mt-1 text-2xl font-bold text-gray-900">৳{{ number_format($user->total_spent ?? 0) }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Member Since</label>
                                    <p class="mt-1 text-lg text-gray-900">{{ $user->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-between">
                            <x-primary-button>
                                {{ __('Update User') }}
                            </x-primary-button>
                            
                            @if($user->user_type !== 'admin' && $user->id !== auth()->id())
                            <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="inline" 
                                  onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                    Delete User
                                </button>
                            </form>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Show/hide reseller section based on user type
        document.getElementById('user_type').addEventListener('change', function() {
            const resellerSection = document.getElementById('reseller-section');
            if (this.value === 'reseller') {
                resellerSection.style.display = 'block';
            } else {
                resellerSection.style.display = 'none';
            }
        });
    </script>
    @endpush
</x-app-layout>
