<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="w-full max-w-md mx-auto">
    <div class="bg-white rounded-lg shadow-md p-8">
        <!-- Login Header -->
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome Back</h2>
            <p class="text-gray-600">Please sign in to your account</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="login" class="space-y-6">
            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email Address')" class="text-gray-700 font-medium" />
                <x-text-input 
                    wire:model.live="form.email" 
                    id="email" 
                    class="block mt-2 w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200" 
                    type="email" 
                    name="email" 
                    placeholder="Enter your email"
                    required 
                    autofocus 
                    autocomplete="username" />
                <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div x-data="{ showPassword: false }">
                <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium" />
                <div class="relative mt-2">
                    <x-text-input 
                        wire:model.live="form.password" 
                        id="password" 
                        class="block w-full px-4 py-3 pr-12 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200"
                        x-bind:type="showPassword ? 'text' : 'password'"
                        name="password"
                        placeholder="Enter your password"
                        required 
                        autocomplete="current-password" />
                    
                    <!-- Password Toggle Button -->
                    <button 
                        type="button"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition duration-200"
                        x-on:click="showPassword = !showPassword">
                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
            </div>

            <!-- Remember Me and Forgot Password -->
            <div class="flex items-center justify-between">
                <label for="remember" class="flex items-center">
                    <input 
                        wire:model="form.remember" 
                        id="remember" 
                        type="checkbox" 
                        class="w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500 focus:ring-2" 
                        name="remember">
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-orange-600 hover:text-orange-800 font-medium transition duration-200" 
                       href="{{ route('password.request') }}" 
                       wire:navigate>
                        Forgot your password?
                    </a>
                @endif
            </div>

            <!-- Login Button -->
            <div>
                <button 
                    type="submit" 
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-200"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed">
                    <span wire:loading.remove>{{ __('Sign In') }}</span>
                    <span wire:loading>
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Signing in...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
