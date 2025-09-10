<section class="font-playfair max-w-7xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between ">
        <h2 class="font-medium text-3xl text-black  mb-6">Areas List</h2>
        <a href="#"
            class="flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg transition">
            <flux:icon name="plus" class="h-5 w-5" />
            Add Area
        </a>
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
</section>
