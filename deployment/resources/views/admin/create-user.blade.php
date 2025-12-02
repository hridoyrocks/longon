{{-- resources/views/admin/create-user.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create New User') }}
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
                    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                        @csrf

                        <!-- Basic Information -->
                        <div class="border-b pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div>
                                    <x-input-label for="name" :value="__('Name')" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" 
                                                  :value="old('name')" required autofocus />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                <!-- Email -->
                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" 
                                                  :value="old('email')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                </div>

                                <!-- Password -->
                                <div>
                                    <x-input-label for="password" :value="__('Password')" />
                                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('password')" />
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" 
                                                  class="mt-1 block w-full" required />
                                </div>
                            </div>
                        </div>

                        <!-- User Type & Credits -->
                        <div class="border-b pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Account Settings</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- User Type -->
                                <div>
                                    <x-input-label for="user_type" :value="__('User Type')" />
                                    <select id="user_type" name="user_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="user">Regular User</option>
                                        <option value="reseller">Reseller</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('user_type')" />
                                </div>

                                <!-- Initial Credits -->
                                <div>
                                    <x-input-label for="credits_balance" :value="__('Initial Credits')" />
                                    <x-text-input id="credits_balance" name="credits_balance" type="number" 
                                                  class="mt-1 block w-full" :value="old('credits_balance', 0)" min="0" />
                                    <x-input-error class="mt-2" :messages="$errors->get('credits_balance')" />
                                </div>
                            </div>
                        </div>

                        <!-- Reseller Information (Hidden by default) -->
                        <div id="reseller-section" class="border-b pb-6" style="display: none;">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Reseller Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Business Name -->
                                <div>
                                    <x-input-label for="business_name" :value="__('Business Name')" />
                                    <x-text-input id="business_name" name="business_name" type="text" 
                                                  class="mt-1 block w-full" :value="old('business_name')" />
                                    <x-input-error class="mt-2" :messages="$errors->get('business_name')" />
                                </div>

                                <!-- Monthly Sales Target -->
                                <div>
                                    <x-input-label for="monthly_sales_target" :value="__('Monthly Sales Target (৳)')" />
                                    <x-text-input id="monthly_sales_target" name="monthly_sales_target" type="number" 
                                                  class="mt-1 block w-full" :value="old('monthly_sales_target', 10000)" min="0" />
                                    <x-input-error class="mt-2" :messages="$errors->get('monthly_sales_target')" />
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center gap-4">
                            <x-primary-button>
                                {{ __('Create User') }}
                            </x-primary-button>
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
