<section class="font-playfair max-w-7xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between py-4">
        <h2 class="font-medium text-3xl text-black mb-6">Areas List</h2>
        <button wire:click="$set('showModal', true)"
            class="flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg transition">
            <flux:icon name="plus-circle" class="h-5 w-5" />
            Add Area
        </button>
    </div>

    <div class="overflow-y-visible bg-white rounded-xl shadow-md border border-gray-200">
        <table class="leading-normal table-auto w-full border-collapse">
            <thead>
                <tr class="bg-orange-100 text-orange-700 font-medium">
                    <th class="p-4 text-center font-medium text-base">SL</th>
                    <th class="p-4 text-center font-medium text-base">Name</th>
                    <th class="p-4 text-center font-medium text-base">Status</th>
                    <th class="p-4 text-center font-medium text-base">Action</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                <!-- Dummy Row -->
                <tr class="hover:bg-orange-50">
                    <td class="p-4 text-center">1</td>
                    <td class="p-4 text-center">John Doe</td>
                    <td class="p-4 text-center">
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                            Active
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <div class="relative inline-block text-left" x-data="{ open: false }"
                            @click.outside="open = false">
                            <button @click="open = !open"
                                class="text-orange-600 rounded-full p-2 hover:bg-orange-100 focus:outline-none transition"
                                title="Settings">
                                <flux:icon name="cog-6-tooth" class="h-5 w-5" />
                            </button>
                            <div x-show="open" x-transition
                                class="absolute right-3 mt-2 p-2 w-44 bg-white border border-gray-200 rounded-lg shadow-lg z-20">
                                <button
                                    class="w-full flex items-center px-3 py-2 rounded text-sm hover:bg-orange-50 transition">
                                    <flux:icon name="eye" class="text-gray-600 mr-2 h-4 w-4" />
                                    Details
                                </button>
                                <button
                                    class="w-full flex items-center px-3 py-2 rounded text-sm hover:bg-orange-50 transition">
                                    <flux:icon name="pencil-square" class="text-gray-600 mr-2 h-4 w-4" />
                                    Edit
                                </button>
                                <button
                                    class="w-full flex items-center px-3 py-2 rounded text-sm hover:bg-orange-50 transition">
                                    <flux:icon name="check-circle" class="text-green-600 mr-2 h-4 w-4" />
                                    Inactive
                                </button>
                                <button
                                    class="w-full flex items-center px-3 py-2 rounded text-sm hover:bg-orange-50 transition">
                                    <flux:icon name="trash" class="text-gray-600 mr-2 h-4 w-4" />
                                    Delete
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Add Area Modal -->
    <div x-show="$wire.showModal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/20 bg-opacity-50"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" wire:keydown.escape="closeModal"
        tabindex="-1">

        <!-- Modal Content -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div @click.away="$wire.closeModal()" class="bg-white rounded-lg p-6 w-full max-w-lg shadow-xl"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="transform scale-95 opacity-0"
                x-transition:enter-end="transform scale-100 opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="transform scale-100 opacity-100"
                x-transition:leave-end="transform scale-95 opacity-0">

                <!-- Modal Header -->
                <div class="flex justify-between items-center pb-3">
                    <h3 class="text-xl font-bold text-gray-800">Add New Area</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <flux:icon name="x-mark" class="h-6 w-6" />
                    </button>
                </div>

                <!-- Modal Body - Form -->
                <form wire:submit.prevent="createArea">
                    <div class="mb-6">
                        <label for="name" class="block text-gray-700 font-semibold mb-2">Area Name</label>
                        <input type="text" id="name" wire:model.defer="name"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex justify-end space-x-4">
                        <button type="button" wire:click="closeModal"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors duration-200">
                            Add Area
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
