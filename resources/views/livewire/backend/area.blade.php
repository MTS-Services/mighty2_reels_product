<section class="font-playfair max-w-7xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between py-4">
        <h2 class="font-medium text-3xl text-black mb-6">Manage your areas</h2>
        <button wire:click="$set('showModal', true)"
            class="flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg transition">
            <flux:icon name="plus-circle" class="h-5 w-5" />
            Add Area
        </button>
    </div>

    @if (session('success'))
        <div class="mb-4 text-green-600 font-medium">{{ session('success') }}</div>
    @endif

    <div class="overflow-y-visible bg-white rounded-xl shadow-md border border-gray-200">
        <table class="leading-normal table-auto w-full border-collapse">
            <thead>
                <tr class="bg-orange-100 text-orange-700 font-medium">
                    <th class="p-4 text-center">SL</th>
                    <th class="p-4 text-center">Name</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @forelse ($cities as $city)
                    <tr class="hover:bg-orange-50">
                        <td class="p-4 text-center">{{ $loop->iteration }}</td>
                        <td class="p-4 text-center">{{ $city->name }}</td>
                        <td class="p-4 text-center">
                            @if ($city->status)
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    Active
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <div class="relative inline-block text-left" x-data="{ open: false }"
                                @click.outside="open = false">
                                <button @click="open = !open"
                                    class="text-orange-600 rounded-full p-2 hover:bg-orange-100 transition"
                                    title="Settings">
                                    <flux:icon name="cog-6-tooth" class="h-5 w-5" />
                                </button>
                                <div x-show="open" x-transition
                                    class="absolute right-3 mt-2 p-2 w-44 bg-white border border-gray-200 rounded-lg shadow-lg z-20">

                                    <button wire:click="details('{{ encrypt($city->id) }}')"
                                        class="w-full flex items-center px-3 py-2 rounded text-sm hover:bg-orange-50">
                                        <flux:icon name="eye" class="text-gray-600 mr-2 h-4 w-4" />
                                        Details
                                    </button>

                                    <button wire:click="edit('{{ encrypt($city->id) }}')"
                                        class="w-full flex items-center px-3 py-2 rounded text-sm hover:bg-orange-50">
                                        <flux:icon name="pencil-square" class="text-gray-600 mr-2 h-4 w-4" />
                                        Edit
                                    </button>

                                    <button wire:click="toggleStatus('{{ encrypt($city->id) }}')"
                                        class="w-full flex items-center px-3 py-2 rounded text-sm hover:bg-orange-50">
                                        @if ($city->status)
                                            <flux:icon name="x-circle" class="text-red-600 mr-2 h-4 w-4" />
                                            Inactive
                                        @else
                                            <flux:icon name="check-circle" class="text-green-600 mr-2 h-4 w-4" />
                                            Active
                                        @endif
                                    </button>

                                    <button wire:click="delete('{{ encrypt($city->id) }}')"
                                        class="w-full flex items-center px-3 py-2 rounded text-sm hover:bg-orange-50">
                                        <flux:icon name="trash" class="text-gray-600 mr-2 h-4 w-4" />
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-4 text-center text-gray-500">No Areas Found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Add/Edit Modal -->
    <div x-show="$wire.showModal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/40" x-transition
        wire:keydown.escape="closeModal" tabindex="-1">
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div @click.away="$wire.closeModal()"
                class="bg-white rounded-lg p-6 w-full max-w-lg shadow-xl transition transform">

                <div class="flex justify-between items-center pb-3">
                    <h3 class="text-xl font-bold text-gray-800">
                        {{ $cityId ? 'Edit Area' : 'Add New Area' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <flux:icon name="x-mark" class="h-6 w-6" />
                    </button>
                </div>

                <form wire:submit.prevent="{{ $cityId ? 'updateArea' : 'createArea' }}">
                    <div class="mb-6">
                        <label for="name" class="block text-gray-700 font-semibold mb-2">Area Name</label>
                        <input type="text" id="name" wire:model.defer="name"
                            class="w-full px-4 py-2 text-black border rounded-lg focus:ring-2 focus:ring-orange-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button type="button" wire:click="closeModal"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
                            {{ $cityId ? 'Update' : 'Add Area' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div x-show="$wire.showDetailsModal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/40" x-transition
        wire:keydown.escape="closeDetailsModal" tabindex="-1">
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div @click.away="$wire.closeDetailsModal()"
                class="bg-white rounded-lg w-full max-w-xl shadow-xl transition transform">

                <div class="flex justify-between items-center pb-5 p-6 rounded-t-lg bg-gray-50">
                    <h3 class="text-xl font-bold text-gray-800">Area Details</h3>
                    <button wire:click="closeDetailsModal" class="text-gray-400 hover:text-gray-600">
                        <flux:icon name="x-mark" class="h-6 w-6" />
                    </button>
                </div>

                <div class="space-y-4 text-gray-700 p-6">
                    <div class="flex justify-between">
                        <span class="font-semibold">Area Name: </span>
                        <span>{{ $name }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="font-semibold">Status: </span>
                        @if ($status)
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                Active
                            </span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                Inactive
                            </span>
                        @endif
                    </div>

                    <x-details :createdAt="$createdAt" :createdBy="$createdBy" :updatedAt="$updatedAt" :updatedBy="$updatedBy" />
                </div>

                <div class="flex justify-end mt-6 m-6">
                    <button type="button" wire:click="closeDetailsModal"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
