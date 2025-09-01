<section>
    <div class="flex flex-col gap-6 max-w-2xl mx-auto p-6 shadow rounded-md">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <img src="https://cdn-icons-png.flaticon.com/512/25/25231.png" alt="logo" class="w-8 h-8">
                <h1 class="text-2xl font-bold">Products</h1>
            </div>
            <div class="flex items-center gap-2">
                <flux:button wire:click="create" variant="primary" class="w-full">
                    {{ __('Create') }}
                </flux:button>
            </div>
        </div>
    </div>
</section>
