<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Authentication</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Floating Label Animation */
            .floating-label-group {
                position: relative;
                margin-bottom: 1.5rem;
            }

            .floating-input {
                width: 100%;
                padding: 1rem 0.75rem 0.5rem 0.75rem;
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
                background: white;
                font-size: 1rem;
                transition: all 0.2s ease;
                color: #250001;
            }

            .floating-input:focus {
                outline: none;
                border-color: #590004;
                box-shadow: 0 0 0 3px rgba(89, 0, 4, 0.1);
            }

            .floating-input.error {
                border-color: #a50104;
                background-color: #fff5f5;
            }

            .floating-label {
                position: absolute;
                left: 0.75rem;
                top: 50%;
                transform: translateY(-50%);
                color: #6b7280;
                font-size: 1rem;
                transition: all 0.2s ease;
                pointer-events: none;
                background: white;
                padding: 0 0.25rem;
            }

            .floating-input:focus + .floating-label,
            .floating-input:not(:placeholder-shown) + .floating-label {
                top: -0.5rem;
                font-size: 0.75rem;
                color: #590004;
                font-weight: 600;
            }

            /* Password Visibility Toggle */
            .password-toggle {
                position: absolute;
                right: 0.75rem;
                top: 50%;
                transform: translateY(-50%);
                cursor: pointer;
                color: #6b7280;
                transition: color 0.2s ease;
            }

            .password-toggle:hover {
                color: #590004;
            }

            /* Gradient Background */
            .brand-gradient {
                background: linear-gradient(135deg, #1a0000 0%, #250001 25%, #590004 75%, #3d0003 100%);
                position: relative;
                overflow: hidden;
            }

            .brand-gradient::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle, rgba(165, 1, 4, 0.15) 0%, transparent 70%);
                animation: pulse 15s ease-in-out infinite;
            }

            @keyframes pulse {
                0%, 100% { transform: translate(0, 0); opacity: 0.5; }
                50% { transform: translate(10%, 10%); opacity: 0.8; }
            }

            /* Elevated Card Shadow */
            .elevated-card {
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 
                            0 10px 10px -5px rgba(0, 0, 0, 0.04),
                            0 0 0 1px rgba(0, 0, 0, 0.05);
            }

            /* Trust Badge */
            .trust-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 1rem;
                background: rgba(255, 255, 255, 0.05);
                border-radius: 9999px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex">
            <!-- Left Panel: Brand Story -->
            <div class="hidden lg:flex lg:w-1/2 brand-gradient items-center justify-center p-12 text-white relative z-10">
                <div class="max-w-md space-y-8">
                    <!-- Logo -->
                    <div class="flex justify-center">
                        <img src="{{ asset('LOGO/csu.png') }}" alt="CSU Logo" class="w-24 h-24 object-contain drop-shadow-lg">
                    </div>

                    <!-- System Title -->
                    <div class="text-center space-y-4">
                        <h1 class="text-4xl font-bold tracking-tight">
                            {{ __('auth_page.system_title') }}
                        </h1>
                        <p class="text-lg text-gray-300 leading-relaxed">
                            {{ __('auth_page.university') }}<br/>
                            <span class="text-sm">{{ __('auth_page.college') }}</span>
                        </p>
                    </div>

                    <!-- Mission Statement -->
                    <div class="mt-8 p-6 bg-white/5 backdrop-blur-sm rounded-lg border border-white/10">
                        <p class="text-sm leading-relaxed text-gray-200 italic">
                            {{ __('auth_page.mission_quote') }}
                        </p>
                    </div>

                    <!-- Trust Badge -->
                    <div class="flex justify-center mt-8">
                        <div class="trust-badge text-sm">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ __('auth_page.security_badge') }}</span>
                        </div>
                    </div>

                    <!-- Language Switcher -->
                    <div class="flex justify-center mt-6">
                        <div class="flex items-center gap-0.5 border border-white/20 rounded-lg p-0.5">
                            <a href="{{ route('language.switch', 'en') }}"
                               class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded {{ app()->getLocale() === 'en' ? 'bg-white/20 text-white' : 'text-white/60 hover:text-white' }} transition-all">
                                🇺🇸 English
                            </a>
                            <a href="{{ route('language.switch', 'fil') }}"
                               class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded {{ app()->getLocale() === 'fil' ? 'bg-white/20 text-white' : 'text-white/60 hover:text-white' }} transition-all">
                                🇵🇭 Filipino
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Action Area -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12" style="background-color: #f3f3f3;">
                <div class="w-full max-w-md">
                    <!-- Mobile Logo (hidden on desktop) -->
                    <div class="lg:hidden flex justify-center mb-8">
                        <x-application-logo class="w-20 h-20 fill-current" style="color: #a50104;" />
                    </div>

                    <!-- Login Card -->
                    <div class="bg-white rounded-xl px-8 py-10 elevated-card">
                        {{ $slot }}
                    </div>

                    <!-- Security Notice -->
                    <div class="mt-6 text-center">
                        <p class="text-xs flex items-center justify-center gap-2" style="color: #590004;">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">{{ __('auth_page.encryption') }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
