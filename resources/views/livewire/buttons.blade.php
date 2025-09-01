<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <flux:icon name="home" class="text-4xl text-gray-600 mb-6" />

        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Button Component Library</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                A comprehensive showcase of the <code class="bg-gray-200 px-2 py-1 rounded text-sm">x-button</code>
                component with all variants, sizes, and states. Built for Laravel 12 with Livewire and Flux.
            </p>
        </div>

        <!-- Basic Variants -->
        <div class="bg-white rounded-2xl shadow-sm border p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">1. Basic Variants</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Primary -->
                <div class="space-y-3">
                    <h3 class="text-lg font-semibold text-gray-700">Primary</h3>
                    <div class="space-y-2">
                        <x-button variant="primary">Primary Button</x-button>
                        <x-button variant="primary" disabled>Disabled</x-button>
                        <x-button variant="primary" loading>Loading...</x-button>
                    </div>
                </div>

                <!-- Secondary -->
                <div class="space-y-3">
                    <h3 class="text-lg font-semibold text-gray-700">Secondary</h3>
                    <div class="space-y-2">
                        <x-button variant="secondary">Secondary Button</x-button>
                        <x-button variant="secondary" disabled>Disabled</x-button>
                        <x-button variant="secondary" loading>Loading...</x-button>
                    </div>
                </div>

                <!-- Success -->
                <div class="space-y-3">
                    <h3 class="text-lg font-semibold text-gray-700">Success</h3>
                    <div class="space-y-2">
                        <x-button variant="success">Success Button</x-button>
                        <x-button variant="success" disabled>Disabled</x-button>
                        <x-button variant="success" loading>Loading...</x-button>
                    </div>
                </div>

                <!-- Danger -->
                <div class="space-y-3">
                    <h3 class="text-lg font-semibold text-gray-700">Danger</h3>
                    <div class="space-y-2">
                        <x-button variant="danger">Danger Button</x-button>
                        <x-button variant="danger" disabled>Disabled</x-button>
                        <x-button variant="danger" loading>Loading...</x-button>
                    </div>
                </div>

                <!-- Warning -->
                <div class="space-y-3">
                    <h3 class="text-lg font-semibold text-gray-700">Warning</h3>
                    <div class="space-y-2">
                        <x-button variant="warning">Warning Button</x-button>
                        <x-button variant="warning" disabled>Disabled</x-button>
                        <x-button variant="warning" loading>Loading...</x-button>
                    </div>
                </div>

                <!-- Info -->
                <div class="space-y-3">
                    <h3 class="text-lg font-semibold text-gray-700">Info</h3>
                    <div class="space-y-2">
                        <x-button variant="info">Info Button</x-button>
                        <x-button variant="info" disabled>Disabled</x-button>
                        <x-button variant="info" loading>Loading...</x-button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Outline Variants -->
        <div class="bg-white rounded-2xl shadow-sm border p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">2. Outline Variants</h2>
            <div class="flex flex-wrap gap-4">
                <x-button variant="primary" outline>Primary Outline</x-button>
                <x-button variant="secondary" outline>Secondary Outline</x-button>
                <x-button variant="success" outline>Success Outline</x-button>
                <x-button variant="danger" outline>Danger Outline</x-button>
                <x-button variant="warning" outline>Warning Outline</x-button>
                <x-button variant="info" outline>Info Outline</x-button>
            </div>
        </div>

        <!-- Size Variants -->
        <div class="bg-white rounded-2xl shadow-sm border p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">3. Size Variants</h2>
            <div class="space-y-4">
                <div class="flex flex-wrap items-center gap-4">
                    <x-button size="sm" variant="primary">Small</x-button>
                    <x-button size="md" variant="primary">Medium</x-button>
                    <x-button size="lg" variant="primary">Large</x-button>
                    <x-button size="xl" variant="primary">Extra Large</x-button>
                </div>
                <div class="flex flex-wrap items-center gap-4">
                    <x-button size="sm" variant="secondary" outline>Small Outline</x-button>
                    <x-button size="md" variant="secondary" outline>Medium Outline</x-button>
                    <x-button size="lg" variant="secondary" outline>Large Outline</x-button>
                    <x-button size="xl" variant="secondary" outline>XL Outline</x-button>
                </div>
            </div>
        </div>

        <!-- Icons -->
        <div class="bg-white rounded-2xl shadow-sm border p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">4. Icons with Flux</h2>
            <div class="space-y-4">
                <div class="flex flex-wrap gap-4">
                    <x-button icon="home" iconPosition="left">Add Item</x-button>
                    <x-button icon="home" iconPosition="left" variant="danger">Delete</x-button>
                    <x-button icon="home" iconPosition="left" variant="warning">Edit</x-button>
                    <x-button icon="home" iconPosition="left" variant="info">View</x-button>
                </div>
                <div class="flex flex-wrap gap-4">
                    <x-button icon="home" iconPosition="right" variant="primary">Continue</x-button>
                    <x-button icon="home" iconPosition="right" variant="secondary">Download</x-button>
                    <x-button icon="home" iconPosition="right" variant="success">Upload</x-button>
                    <x-button icon="home" iconPosition="right" variant="info">Share</x-button>
                </div>
            </div>
        </div>

        <!-- Dashed Styles -->
        <div class="bg-white rounded-2xl shadow-sm border p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">5. Dashed Borders</h2>
            <div class="flex flex-wrap gap-4">
                <x-button dashed>Dashed Border</x-button>
                <x-button variant="primary" outline dashed>Dashed Outline</x-button>
                <x-button variant="secondary" outline dashed>Dashed Secondary</x-button>
                <x-button variant="success" outline dashed icon="home">Dashed with Icon</x-button>
            </div>
        </div>

        <!-- Link Text Variants -->
        <div class="bg-white rounded-2xl shadow-sm border p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">6. Link Text Styles</h2>
            <div class="space-y-4">
                <div class="flex flex-wrap gap-6">
                    <x-button linkText href="#">Basic Link</x-button>
                    <x-button linkText underline href="#">Underlined Link</x-button>
                    <x-button linkText disabled href="#">Disabled Link</x-button>
                </div>
                <div class="flex flex-wrap gap-6">
                    <x-button linkText icon="home" iconPosition="right" href="#">Read More</x-button>
                    <x-button linkText icon="home" iconPosition="right" href="#" target="_blank">External
                        Link</x-button>
                    <x-button linkText icon="home" iconPosition="left" href="#">Download File</x-button>
                </div>
            </div>
        </div>

        <!-- Block Buttons -->
        <div class="bg-white rounded-2xl shadow-sm border p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">7. Block (Full Width) Buttons</h2>
            <div class="space-y-4">
                <x-button block variant="primary">Primary Block Button</x-button>
                <x-button block variant="secondary" outline>Secondary Outline Block</x-button>
                <x-button block variant="success" icon="home" iconPosition="left">Success Block with
                    Icon</x-button>
                <x-button block variant="danger" dashed outline>Danger Dashed Block</x-button>
            </div>
        </div>

        <!-- Livewire Actions -->
        <div class="bg-white rounded-2xl shadow-sm border p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">8. Livewire Actions & Forms</h2>
            <div class="space-y-4">
                <div class="flex flex-wrap gap-4">
                    <x-button action="saveData" variant="primary" icon="home">Save Data</x-button>
                    <x-button action="deleteItem" variant="danger" icon="home">Delete Item</x-button>
                    <x-button action="refreshPage" variant="secondary" icon="home">Refresh</x-button>
                </div>

                <form wire:submit.prevent="submitForm" class="space-y-4">
                    <div class="flex flex-wrap gap-4">
                        <x-button type="submit" action="submitForm" variant="primary" icon="home">Submit
                            Form</x-button>
                        <x-button type="reset" variant="secondary" outline>Reset Form</x-button>
                        <x-button type="button" action="cancelForm" variant="danger" outline>Cancel</x-button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Links and Navigation -->
        <div class="bg-white rounded-2xl shadow-sm border p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">9. Links & Navigation</h2>
            <div class="space-y-4">
                <div class="flex flex-wrap gap-4">
                    <x-button href="/dashboard" variant="primary">Dashboard Link</x-button>
                    <x-button href="/profile" variant="secondary">Profile Link</x-button>
                    <x-button href="https://example.com" target="_blank" variant="info" icon="home"
                        iconPosition="right">External Link</x-button>
                </div>

                @if (Route::has('home'))
                    <div class="flex flex-wrap gap-4">
                        <x-button route="home" variant="primary" outline>Home Route</x-button>
                        <x-button route="home" variant="secondary" wireNavigate="{{ false }}">Home (No
                            Navigate)</x-button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Advanced Combinations -->
        <div class="bg-white rounded-2xl shadow-sm border p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">10. Advanced Combinations</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <x-button variant="primary" size="lg" icon="home" iconPosition="left" block>Large
                        Primary with Icon</x-button>
                    <x-button variant="secondary" outline dashed size="md" icon="home" iconPosition="right"
                        block>Dashed Outline Secondary</x-button>
                    <x-button variant="success" size="sm" icon="home" iconPosition="left" block>Small
                        Success Block</x-button>
                </div>
                <div class="space-y-3">
                    <x-button href="/admin" variant="danger" outline size="lg" icon="home"
                        iconPosition="left" block>Admin Link</x-button>
                    <x-button action="complexAction" variant="warning" dashed size="md" icon="home" loading
                        block>Complex Action</x-button>
                    <x-button linkText underline icon="home" iconPosition="right" href="#"
                        class="justify-start w-full">Full Width Link Text</x-button>
                </div>
            </div>
        </div>

        <!-- Custom Variants -->
        <div class="bg-white rounded-2xl shadow-sm border p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">11. Custom Variants</h2>
            <p class="text-gray-600 mb-4">Pass custom CSS classes as the variant prop for unlimited customization.</p>
            <div class="flex flex-wrap gap-4">
                <x-button
                    variant="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white rounded-xl px-6 py-3 shadow-lg hover:shadow-xl transform hover:scale-105">
                    Gradient Button
                </x-button>
                <x-button
                    variant="bg-black text-white hover:bg-gray-800 rounded-full px-8 py-3 border-2 border-black hover:border-gray-800">
                    Rounded Black
                </x-button>
                <x-button
                    variant="bg-transparent border-2 border-indigo-500 text-indigo-600 hover:bg-indigo-500 hover:text-white rounded-lg px-4 py-2 font-bold">
                    Custom Outline
                </x-button>
            </div>
        </div>

        <!-- Interactive States -->
        <div class="bg-white rounded-2xl shadow-sm border p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">12. Interactive Examples</h2>
            <p class="text-gray-600 mb-4">These buttons demonstrate various interactive states and hover effects.</p>

            <div class="space-y-6">
                <!-- Hover Effects -->
                <div class="space-y-2">
                    <h3 class="text-base font-medium text-gray-700">Hover Effects</h3>
                    <div class="flex flex-wrap gap-4">
                        <x-button variant="primary" class="hover:scale-105 transform transition-transform">Scale on
                            Hover</x-button>
                        <x-button variant="secondary" class="hover:shadow-lg transition-shadow">Shadow on
                            Hover</x-button>
                        <x-button variant="success" outline
                            class="hover:rotate-1 transform transition-transform">Slight Rotate</x-button>
                    </div>
                </div>

                <!-- Icon Combinations -->
                <div class="space-y-2">
                    <h3 class="text-base font-medium text-gray-700">Icon Combinations</h3>
                    <div class="flex flex-wrap gap-4">
                        <x-button icon="home" iconPosition="left" variant="primary">Add User</x-button>
                        <x-button icon="home" iconPosition="right" variant="secondary">Next Step</x-button>
                        <x-button icon="home" iconPosition="left" variant="success" outline>Download
                            Report</x-button>
                        <x-button icon="home" iconPosition="left" variant="danger"
                            iconClasses="text-red-500">Like</x-button>
                    </div>
                </div>

                <!-- Loading States -->
                <div class="space-y-2">
                    <h3 class="text-base font-medium text-gray-700">Loading States</h3>
                    <div class="flex flex-wrap gap-4">
                        <x-button action="slowAction" variant="primary" loading>Processing...</x-button>
                        <x-button action="uploadFile" variant="secondary" icon="home"
                            loading>Uploading...</x-button>
                        <x-button action="saveChanges" variant="success" outline loading>Saving Changes...</x-button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Integration -->
        <div class="bg-white rounded-2xl shadow-sm border p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">13. Form Integration</h2>

            <form wire:submit.prevent="handleFormSubmit" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sample Input</label>
                        <input type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter some text">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Another Input</label>
                        <select
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option>Option 1</option>
                            <option>Option 2</option>
                            <option>Option 3</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap gap-4">
                    <x-button type="submit" action="handleFormSubmit" variant="primary" icon="home"
                        loading>Submit Form</x-button>
                    <x-button type="reset" variant="secondary" outline icon="home">Reset Form</x-button>
                    <x-button type="button" action="saveDraft" variant="info" outline icon="home">Save
                        Draft</x-button>
                    <x-button type="button" href="/cancel" variant="danger" linkText>Cancel</x-button>
                </div>
            </form>
        </div>

        <!-- Real-world Examples -->
        <div class="bg-white rounded-2xl shadow-sm border p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">14. Real-world Usage Examples</h2>

            <div class="space-y-8">
                <!-- Card Actions -->
                <div class="border rounded-lg p-6 bg-gray-50">
                    <h3 class="text-lg font-semibold mb-2">User Profile Card</h3>
                    <p class="text-gray-600 mb-4">John Doe - Software Developer</p>
                    <div class="flex flex-wrap gap-3">
                        <x-button size="sm" variant="primary" icon="home" iconPosition="left">View
                            Profile</x-button>
                        <x-button size="sm" variant="secondary" outline icon="home">Edit</x-button>
                        <x-button size="sm" variant="danger" outline icon="home">Delete</x-button>
                        <x-button size="sm" linkText icon="home" iconPosition="left"
                            href="mailto:john@example.com">Send Email</x-button>
                    </div>
                </div>

                <!-- Data Table Actions -->
                <div class="border rounded-lg p-6 bg-gray-50">
                    <h3 class="text-lg font-semibold mb-2">Data Table Actions</h3>
                    <div class="flex flex-wrap justify-between items-center gap-4">
                        <div class="flex flex-wrap gap-2">
                            <x-button size="sm" variant="primary" icon="home">Add New</x-button>
                            <x-button size="sm" variant="secondary" outline icon="home">Filter</x-button>
                            <x-button size="sm" variant="info" outline icon="home">Export</x-button>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <x-button size="sm" action="selectAll" variant="secondary" icon="home">Select
                                All</x-button>
                            <x-button size="sm" action="deleteSelected" variant="danger" outline icon="home"
                                disabled>Delete Selected</x-button>
                        </div>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <div class="border rounded-lg p-6 bg-gray-50">
                    <h3 class="text-lg font-semibold mb-2">Navigation Menu</h3>
                    <div class="flex flex-wrap gap-4">
                        <x-button href="/dashboard" variant="primary" wireNavigate>Dashboard</x-button>
                        <x-button href="/users" variant="secondary" icon="home"
                            iconPosition="left">Users</x-button>
                        <x-button href="/settings" variant="secondary" icon="home"
                            iconPosition="left">Settings</x-button>
                        <x-button href="/reports" variant="info" outline icon="home"
                            iconPosition="left">Reports</x-button>
                        <x-button action="logout" variant="danger" linkText icon="home"
                            iconPosition="right">Logout</x-button>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="border rounded-lg p-6 bg-gray-50">
                    <h3 class="text-lg font-semibold mb-2">Modal Dialog Actions</h3>
                    <p class="text-gray-600 mb-4">Are you sure you want to delete this item?</p>
                    <div class="flex flex-wrap gap-3">
                        <x-button action="confirmDelete" variant="danger" icon="home" loading>Delete
                            Item</x-button>
                        <x-button action="cancelDelete" variant="secondary" outline>Cancel</x-button>
                    </div>
                </div>

                <!-- E-commerce Actions -->
                <div class="border rounded-lg p-6 bg-gray-50">
                    <h3 class="text-lg font-semibold mb-2">E-commerce Product Actions</h3>
                    <div class="space-y-3">
                        <div class="flex flex-wrap gap-3">
                            <x-button action="addToCart" variant="success" icon="home" iconPosition="left"
                                size="lg">Add to Cart</x-button>
                            <x-button action="buyNow" variant="primary" icon="home" iconPosition="left"
                                size="lg">Buy Now</x-button>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <x-button action="addToWishlist" variant="secondary" outline icon="home"
                                iconPosition="left">Add to Wishlist</x-button>
                            <x-button href="/compare" variant="info" linkText icon="home"
                                iconPosition="left">Compare</x-button>
                            <x-button action="shareProduct" variant="secondary" linkText icon="home"
                                iconPosition="left">Share</x-button>
                        </div>
                    </div>
                </div>

                <!-- File Upload Interface -->
                <div class="border rounded-lg p-6 bg-gray-50">
                    <h3 class="text-lg font-semibold mb-2">File Upload Interface</h3>
                    <div class="space-y-4">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                            <p class="text-gray-500 mb-4">Drag and drop files here or click to browse</p>
                            <x-button variant="primary" outline dashed icon="home" iconPosition="left">Choose
                                Files</x-button>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <x-button action="uploadFiles" variant="success" icon="home" loading>Upload
                                Files</x-button>
                            <x-button action="clearFiles" variant="secondary" outline icon="home">Clear
                                All</x-button>
                            <x-button action="previewFiles" variant="info" linkText icon="home">Preview</x-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Component Usage Guide -->
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-8">
            <h2 class="text-2xl font-bold text-blue-900 mb-4">📚 Quick Usage Guide</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <div>
                    <h3 class="font-semibold text-blue-800 mb-2">Basic Usage:</h3>
                    <code class="block bg-white p-3 rounded border text-xs">
                        <x-button>Click Me</x-button><br>
                        <x-button variant="secondary">Secondary</x-button><br>
                        <x-button variant="success" icon="home">Success</x-button>
                    </code>
                </div>
                <div>
                    <h3 class="font-semibold text-blue-800 mb-2">Livewire Actions:</h3>
                    <code class="block bg-white p-3 rounded border text-xs">
                        <x-button action="save" loading>Save</x-button><br>
                        <x-button type="submit" action="submit">Submit</x-button><br>
                        <x-button action="delete" variant="danger">Delete</x-button>
                    </code>
                </div>
                <div>
                    <h3 class="font-semibold text-blue-800 mb-2">Links & Routes:</h3>
                    <code class="block bg-white p-3 rounded border text-xs">
                        <x-button href="/dashboard">Dashboard</x-button><br>
                        <x-button route="home">Home</x-button><br>
                        <x-button href="#" linkText>Text Link</x-button>
                    </code>
                </div>
                <div>
                    <h3 class="font-semibold text-blue-800 mb-2">Modifiers:</h3>
                    <code class="block bg-white p-3 rounded border text-xs">
                        <x-button outline>Outlined</x-button><br>
                        <x-button dashed>Dashed Border</x-button><br>
                        <x-button block>Full Width</x-button><br>
                        <x-button size="lg" disabled>Large Disabled</x-button>
                    </code>
                </div>
            </div>
        </div>

    </div>
</div>
