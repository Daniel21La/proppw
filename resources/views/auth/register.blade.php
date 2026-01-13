<x-guest-layout>
    <!-- Custom Styles -->
    <style>
        /* Ensure full screen coverage */
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        .animation-delay-500 {
            animation-delay: 0.5s;
        }
        
        .animation-delay-1000 {
            animation-delay: 1s;
        }
        
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        
        .animation-delay-2500 {
            animation-delay: 2.5s;
        }
        
        .animation-delay-3000 {
            animation-delay: 3s;
        }
        
        .animation-delay-4000 {
            animation-delay: 4s;
        }
        
        .bg-grid-pattern {
            background-image: 
                linear-gradient(90deg, rgba(59,130,246,0.1) 1px, transparent 1px),
                linear-gradient(rgba(59,130,246,0.1) 1px, transparent 1px);
            background-size: 20px 20px;
            background-position: 0 0, 0 0;
        }
        
        /* Glassmorphism effect */
        .backdrop-blur-xl {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        
        /* Smooth transitions */
        * {
            transition: all 0.3s ease;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(45deg, #6366f1, #8b5cf6);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(45deg, #4f46e5, #7c3aed);
        }
        
        /* Full screen background coverage */
        .fixed {
            position: fixed;
        }
        
        .inset-0 {
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }
    </style>

    <!-- Modern Background - Full Screen -->
    <div class="fixed inset-0 min-h-screen w-full bg-gradient-to-br from-indigo-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 w-full h-full overflow-hidden">
            <!-- Animated Background Elements -->
            <div class="absolute inset-0 w-full h-full overflow-hidden">
                <div class="absolute -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse"></div>
                <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-br from-blue-400 to-indigo-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse animation-delay-2000"></div>
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-br from-indigo-400 to-purple-400 rounded-full mix-blend-multiply filter blur-xl opacity-10 animate-pulse animation-delay-4000"></div>
                
                <!-- Additional background elements for full coverage -->
                <div class="absolute top-10 left-10 w-80 h-80 bg-gradient-to-br from-cyan-400 to-blue-400 rounded-full mix-blend-multiply filter blur-xl opacity-15 animate-pulse animation-delay-1000"></div>
                <div class="absolute bottom-10 right-10 w-80 h-80 bg-gradient-to-br from-pink-400 to-rose-400 rounded-full mix-blend-multiply filter blur-xl opacity-15 animate-pulse animation-delay-3000"></div>
                
                <!-- Floating particles -->
                <div class="absolute top-20 left-20 w-3 h-3 bg-indigo-400 rounded-full animate-float"></div>
                <div class="absolute top-40 right-32 w-2 h-2 bg-purple-400 rounded-full animate-float animation-delay-1000"></div>
                <div class="absolute bottom-32 left-1/4 w-4 h-4 bg-pink-400 rounded-full animate-float animation-delay-2000"></div>
                <div class="absolute bottom-20 right-20 w-3 h-3 bg-blue-400 rounded-full animate-float animation-delay-3000"></div>
                <div class="absolute top-1/3 left-1/3 w-2 h-2 bg-cyan-400 rounded-full animate-float animation-delay-500"></div>
                <div class="absolute top-2/3 right-1/3 w-2 h-2 bg-rose-400 rounded-full animate-float animation-delay-2500"></div>
            </div>
            
            <!-- Grid Pattern - Full Coverage -->
            <div class="absolute inset-0 w-full h-full bg-grid-pattern opacity-5 dark:opacity-10"></div>
        </div>
        
        <!-- Content Container - Positioned over background -->
        <div class="relative z-10 flex items-center justify-center min-h-screen w-full p-4">
            <div class="w-full max-w-md">
                <!-- Session Status -->
                <x-auth-session-status class="mb-6" :status="session('status')" />
                
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl shadow-2xl rounded-3xl p-8 border border-white/20 dark:border-gray-700/50 w-full">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            {{ __('Create Account') }}
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-2">
                            {{ __('Join us and get started') }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                        @csrf

                        <!-- Name -->
                        <div class="group">
                            <x-input-label for="name" :value="__('Name')" 
                                class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 block" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <x-text-input id="name" 
                                    class="block w-full pl-10 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl 
                                           focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-800 
                                           transition-all duration-200 bg-gray-50 dark:bg-gray-800 
                                           hover:border-gray-300 dark:hover:border-gray-500" 
                                    type="text" 
                                    name="name" 
                                    :value="old('name')" 
                                    required 
                                    autofocus 
                                    autocomplete="name" 
                                    placeholder="Enter your full name" />
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="group">
                            <x-input-label for="email" :value="__('Email')" 
                                class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 block" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>
                                <x-text-input id="email" 
                                    class="block w-full pl-10 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl 
                                           focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-800 
                                           transition-all duration-200 bg-gray-50 dark:bg-gray-800 
                                           hover:border-gray-300 dark:hover:border-gray-500" 
                                    type="email" 
                                    name="email" 
                                    :value="old('email')" 
                                    required 
                                    autocomplete="username" 
                                    placeholder="your@email.com" />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="group">
                            <x-input-label for="password" :value="__('Password')" 
                                class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 block" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <x-text-input id="password" 
                                    class="block w-full pl-10 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl 
                                           focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-800 
                                           transition-all duration-200 bg-gray-50 dark:bg-gray-800 
                                           hover:border-gray-300 dark:hover:border-gray-500"
                                    type="password"
                                    name="password"
                                    required 
                                    autocomplete="new-password" 
                                    placeholder="Create a strong password" />
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="group">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" 
                                class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 block" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <x-text-input id="password_confirmation" 
                                    class="block w-full pl-10 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-xl 
                                           focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-800 
                                           transition-all duration-200 bg-gray-50 dark:bg-gray-800 
                                           hover:border-gray-300 dark:hover:border-gray-500"
                                    type="password"
                                    name="password_confirmation"
                                    required 
                                    autocomplete="new-password" 
                                    placeholder="Confirm your password" />
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Register Button -->
                        <div class="pt-4">
                            <x-primary-button class="w-full py-3 px-4 bg-gradient-to-r from-indigo-600 to-purple-600 
                                                     hover:from-indigo-700 hover:to-purple-700 
                                                     focus:ring-4 focus:ring-indigo-200 dark:focus:ring-indigo-800 
                                                     transform hover:scale-[1.02] transition-all duration-200 
                                                     rounded-xl font-semibold text-white shadow-lg hover:shadow-xl">
                                <div class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                    </svg>
                                    {{ __('Create Account') }}
                                </div>
                            </x-primary-button>
                        </div>

                        <!-- Divider -->
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 bg-white dark:bg-gray-900 text-gray-500 dark:text-gray-400">
                                    {{ __('or sign up with') }}
                                </span>
                            </div>
                        </div>
                    </form>

                    <!-- Footer -->
                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Already have an account?') }}
                            <a href="{{ route('login') }}" class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition-colors">
                                {{ __('Sign in') }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>