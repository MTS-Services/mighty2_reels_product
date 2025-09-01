<section>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class=" overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                {{ __('Welcome to our website!') }}
                <a href="#" class="btn btn-primary" wire:navigate>
                    {{ __('Get Started') }}
                </a>

                <button class="btn btn-danger">{{ __('Log out') }}</button>
            </div>
        </div>
    </div>
</section>
