<section>

    <div>
        <div class="bg-gray-50 min-h-screen">
            <div>
                <div class="max-w-6xl mx-auto px-4 py-4">
                    <a href="{{ route('admin.products') }}" wire:navigate
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium tracking-wide text-white transition-colors duration-200 rounded-md bg-neutral-950 hover:bg-neutral-900 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-900 focus:shadow-outline focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to products
                    </a>
                </div>
            </div>
            <div class="max-w-6xl mx-auto px-4 py-8">
                <div class="grid md:grid-cols-2 gap-12">
                    <div>
                        <div class="bg-gradient-to-br   rounded-2xl p-8 mb-6 flex items-center justify-center">
                            <img src="{{ storage_url($product->images->first()->file) }}" alt="{{ $product->title }}"
                                class="w-full h-80">
                        </div>
                        @if ($product->images->count() > 1)
                            <div class="flex space-x-3 justify-center items-center">
                                <!-- Previous Button -->
                                <button class="text-orange-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>

                                <!-- Thumbnails -->
                                @foreach ($product->images as $image)
                                    <div wire:click="changeImage('{{ $image->file }}')"
                                        class="w-20 h-24 bg-gray-100 rounded-lg border flex items-center justify-center hover:border-orange-400 cursor-pointer transition-colors
                                         {{ $activeImage === $image->file ? 'border-orange-500 border-2' : 'border-gray-200' }}">
                                        <img src="{{ storage_url($image->file) }}" class="w-12 h-12 object-contain">
                                    </div>
                                @endforeach


                                <!-- Next Button -->
                                <button class="text-orange-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m12.75 15 3-3m0 0-3-3m3 3h-7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                            </div>
                        @endif


                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->title }}</h1>

                        <div class="bg-white rounded-xl p-6 shadow-sm border mb-6">

                            <p class="text-gray-700 leading-relaxed mb-3">
                                {!! nl2br(e($product->description)) !!}
                            </p>

                        </div>

                    </div>
                </div>
            </div>

            <footer class="bg-[#EFEFEF] p-2 mt-20">
                <div class="max-w-6xl mx-auto px-4 py-2">
                    <p class="text-center text-orange-500 font-medium">© 2025 Reelshop. All rights reserved.</p>
                </div>
            </footer>
        </div>
    </div>


</section>
