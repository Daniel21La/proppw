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
                {{ __('Welcome Back') }}
            </h2>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                {{ __('Sign in to your account') }}
            </p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

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
                        autofocus 
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
                        autocomplete="current-password" 
                        placeholder="••••••••" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="flex items-center group cursor-pointer">
                    <input id="remember_me" 
                           type="checkbox" 
                           class="rounded-lg border-2 border-gray-300 dark:border-gray-600 
                                  text-indigo-600 shadow-sm focus:ring-indigo-500 focus:ring-2 
                                  dark:bg-gray-800 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800 
                                  transition-all duration-200 hover:border-indigo-400" 
                           name="remember">
                    <span class="ml-3 text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-800 dark:group-hover:text-gray-200 transition-colors">
                        {{ __('Remember me') }}
                    </span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 
                             font-medium transition-colors duration-200 hover:underline" 
                       href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <!-- Login Button -->
            <div class="pt-4">
                <x-primary-button class="w-full py-3 px-4 bg-gradient-to-r from-indigo-600 to-purple-600 
                                         hover:from-indigo-700 hover:to-purple-700 
                                         focus:ring-4 focus:ring-indigo-200 dark:focus:ring-indigo-800 
                                         transform hover:scale-[1.02] transition-all duration-200 
                                         rounded-xl font-semibold text-white shadow-lg hover:shadow-xl">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        {{ __('Sign In') }}
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
                        {{ __('or continue with') }}
                    </span>
                </div>
            </div>
        </form>

        <!-- Footer -->
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __("Don't have an account?") }}
                <a href="{{ route('register') }}" class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition-colors">
                    {{ __('Sign up') }}
                </a>
            </p>
            </div>
        </div>
    </div>
</div>

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
</x-guest-layout>