<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div class="w-full max-w-md mx-auto" x-data="{ showPassword: false, showPasswordConfirmation: false }">
    <div class="bg-white rounded-lg shadow-md p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Reset Password</h2>
            <p class="text-gray-600">Enter your new password below</p>
        </div>

        <form wire:submit="resetPassword" class="space-y-6">
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
                    autofocus 
                    autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('New Password')" class="text-gray-700 font-medium" />
                <div class="relative mt-2">
                    <x-text-input 
                        wire:model.live="password" 
                        id="password" 
                        class="block w-full px-4 py-3 pr-12 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200" 
                        x-bind:type="showPassword ? 'text' : 'password'"
                        name="password" 
                        placeholder="Enter your new password"
                        required 
                        autocomplete="new-password" />
                    
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
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm New Password')" class="text-gray-700 font-medium" />
                <div class="relative mt-2">
                    <x-text-input 
                        wire:model.live="password_confirmation" 
                        id="password_confirmation" 
                        class="block w-full px-4 py-3 pr-12 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200"
                        x-bind:type="showPasswordConfirmation ? 'text' : 'password'"
                        name="password_confirmation" 
                        placeholder="Confirm your new password"
                        required 
                        autocomplete="new-password" />

                    <!-- Password Toggle Button -->
                    <button 
                        type="button"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition duration-200"
                        x-on:click="showPasswordConfirmation = !showPasswordConfirmation">
                        <svg x-show="!showPasswordConfirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg x-show="showPasswordConfirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Submit Button -->
            <div>
                <button 
                    type="submit" 
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-200"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed">
                    <span wire:loading.remove>{{ __('Reset Password') }}</span>
                    <span wire:loading>
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Resetting...
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
