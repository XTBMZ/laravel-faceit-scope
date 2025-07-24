
<footer class="bg-faceit-card border-t border-gray-800 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="flex items-center space-x-2 mb-4 md:mb-0">
            </div>
            <div class="flex space-x-6 text-sm text-gray-400">
                <a href="{{ route('about') }}" class="hover:text-white transition-colors">{{ __('footer.about') }}</a>
                <a href="{{ route('privacy') }}" class="hover:text-white transition-colors">{{ __('footer.privacy') }}</a>
                <a href="{{ route('contact') }}" class="hover:text-white transition-colors">{{ __('footer.contact') }}</a>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-6 pt-6 text-center text-sm text-gray-500">
            {{ date('Y') }} Faceit Scope. {{ __('footer.data_provided') }}
        </div>
    </div>
</footer>