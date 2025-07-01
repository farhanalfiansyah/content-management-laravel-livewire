<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" 
            class="flex items-center space-x-2 px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
        <!-- Current Language Flag -->
        <span class="text-lg">
            @if(app()->getLocale() === 'en')
                🇺🇸
            @elseif(app()->getLocale() === 'id')
                🇮🇩
            @elseif(app()->getLocale() === 'es')
                🇪🇸
            @elseif(app()->getLocale() === 'fr')
                🇫🇷
            @elseif(app()->getLocale() === 'de')
                🇩🇪
            @elseif(app()->getLocale() === 'ar')
                🇸🇦
            @endif
        </span>
        
        <!-- Language Code -->
        <span class="uppercase font-semibold">{{ app()->getLocale() }}</span>
        
        <!-- Dropdown Arrow -->
        <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
        
        <!-- English -->
        <a href="{{ route('language.switch', 'en') }}" 
           class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200 {{ app()->getLocale() === 'en' ? 'bg-orange-50 text-orange-600' : '' }}">
            <span class="text-lg">🇺🇸</span>
            <div>
                <div class="font-medium">English</div>
                <div class="text-xs text-gray-500">English</div>
            </div>
            @if(app()->getLocale() === 'en')
                <svg class="w-4 h-4 ml-auto text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            @endif
        </a>

        <!-- Indonesian -->
        <a href="{{ route('language.switch', 'id') }}" 
           class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200 {{ app()->getLocale() === 'id' ? 'bg-orange-50 text-orange-600' : '' }}">
            <span class="text-lg">🇮🇩</span>
            <div>
                <div class="font-medium">Indonesian</div>
                <div class="text-xs text-gray-500">Bahasa Indonesia</div>
            </div>
            @if(app()->getLocale() === 'id')
                <svg class="w-4 h-4 ml-auto text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            @endif
        </a>

        <!-- Spanish -->
        <a href="{{ route('language.switch', 'es') }}" 
           class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200 {{ app()->getLocale() === 'es' ? 'bg-orange-50 text-orange-600' : '' }}">
            <span class="text-lg">🇪🇸</span>
            <div>
                <div class="font-medium">Spanish</div>
                <div class="text-xs text-gray-500">Español</div>
            </div>
            @if(app()->getLocale() === 'es')
                <svg class="w-4 h-4 ml-auto text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            @endif
        </a>

        <!-- French -->
        <a href="{{ route('language.switch', 'fr') }}" 
           class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200 {{ app()->getLocale() === 'fr' ? 'bg-orange-50 text-orange-600' : '' }}">
            <span class="text-lg">🇫🇷</span>
            <div>
                <div class="font-medium">French</div>
                <div class="text-xs text-gray-500">Français</div>
            </div>
            @if(app()->getLocale() === 'fr')
                <svg class="w-4 h-4 ml-auto text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            @endif
        </a>
    </div>
</div> 