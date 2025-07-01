<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div class="w-full max-w-md mx-auto">
    <div class="bg-white rounded-lg shadow-md p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Forgot Password?</h2>
            <p class="text-gray-600">No problem. Enter your email address and we'll send you a password reset link.</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-6" :status="session('status')" />

        <form wire:submit="sendPasswordResetLink" class="space-y-6">
            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email Address')" class="text-gray-700 font-medium" />
                <x-text-input 
                    wire:model.live="email" 
                    id="email" 
                    class="block mt-2 w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200" 
                    type="email" 
                    name="email" 
                    placeholder="Enter your email address"
                    required 
                    autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Submit Button -->
            <div>
                <button 
                    type="submit" 
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-200"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed">
                    <span wire:loading.remove>{{ __('Send Reset Link') }}</span>
                    <span wire:loading>
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Sending...
                    </span>
                </button>
            </div>

            <!-- Back to Login Link -->
            <div class="text-center">
                <a class="text-sm text-orange-600 hover:text-orange-800 font-medium transition duration-200" 
                   href="{{ route('login') }}" 
                   wire:navigate>
                    ← Back to Sign In
                </a>
            </div>
        </form>
    </div>
</div>
