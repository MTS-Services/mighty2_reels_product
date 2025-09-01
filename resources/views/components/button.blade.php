@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'route' => null,
    'type' => 'button',
    'target' => '_self',
    'outline' => false,
    'dashed' => false,
    'linkText' => false,
    'underline' => false,
    'block' => false,
    'disabled' => false,
    'loading' => false,
    'icon' => null,
    'iconPosition' => 'left',
    'iconClasses' => '',
    'wireNavigate' => true,
    'action' => null,
])

@php
    // Determine if this should be a link or button
    $isLink = !empty($href) || !empty($route);

    // Build the final href
    $finalHref = '';
    if ($route && Route::has($route)) {
        $finalHref = route($route);
    } elseif ($href) {
        $finalHref = $href;
    }

    // Base classes for all buttons
    $baseClasses =
        'relative inline-flex items-center justify-center font-medium font-inter transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

    // Size variations - using array approach to avoid switch issues
    $sizeVariants = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-6 py-3 text-lg',
        'xl' => 'px-8 py-4 text-xl',
    ];
    $sizeClasses = $sizeVariants[$size] ?? $sizeVariants['md'];

    // Variant styles with custom colors
    $variantClasses = '';

    if ($linkText) {
        // Link text style overrides everything - using custom orange
        $variantClasses =
            'bg-transparent hover:text-[var(--color-orange-normal-hover)] focus:ring-[var(--color-orange-normal)] rounded-lg';
        $variantClasses .= ' text-[var(--color-orange-normal)]';
    } elseif ($outline) {
        // Outline styles with custom colors
        $outlineVariants = [
            'primary' =>
                'bg-transparent border text-[var(--color-orange-normal)] border-[var(--color-orange-normal)] hover:bg-[var(--color-orange-light)] focus:ring-[var(--color-orange-normal)] rounded-lg',
            'secondary' =>
                'bg-transparent border text-[var(--color-orange-normal)] border-[var(--color-orange-normal)] hover:bg-[var(--color-orange-light-hover)] focus:ring-[var(--color-orange-light)] rounded-lg',
            'success' =>
                'bg-transparent border border-green-500 text-green-600 hover:bg-green-50 focus:ring-green-500 rounded-lg',
            'danger' =>
                'bg-transparent border text-[var(--color-red-normal)] border-[var(--color-red-normal)] hover:bg-[var(--color-red-light)] focus:ring-[var(--color-red-normal)] rounded-lg',
            'warning' =>
                'bg-transparent border text-[var(--color-yellow-normal)] border-[var(--color-yellow-normal)] hover:bg-[var(--color-yellow-light)] focus:ring-[var(--color-yellow-normal)] rounded-lg',
            'info' =>
                'bg-transparent border border-cyan-500 text-cyan-600 hover:bg-cyan-50 focus:ring-cyan-500 rounded-lg',
        ];
        $variantClasses = $outlineVariants[$variant] ?? $outlineVariants['primary'];
    } else {
        // Solid styles with custom colors
        $solidVariants = [
            'primary' =>
                'bg-[var(--color-orange-normal)] text-white hover:bg-[var(--color-orange-normal-hover)] focus:ring-[var(--color-orange-normal)] rounded-lg',
            'secondary' =>
                'bg-[var(--color-orange-light)] text-[var(--color-orange-normal)] hover:bg-[var(--color-orange-light-hover)] focus:ring-[var(--color-orange-light)] rounded-lg border border-[var(--color-orange-normal)]',
            'success' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500 rounded-lg',
            'danger' =>
                'bg-[var(--color-red-normal)] text-white hover:bg-[var(--color-red-normal-hover)] focus:ring-[var(--color-red-normal)] rounded-lg',
            'warning' =>
                'bg-[var(--color-yellow-normal)] text-white hover:bg-[var(--color-yellow-normal-hover)] focus:ring-[var(--color-yellow-normal)] rounded-lg',
            'info' => 'bg-cyan-600 text-white hover:bg-cyan-700 focus:ring-cyan-500 rounded-lg',
        ];

        // Check if it's a predefined variant or custom classes
    if (array_key_exists($variant, $solidVariants)) {
        $variantClasses = $solidVariants[$variant];
    } else {
        // Treat as custom classes if not a predefined variant
        $variantClasses = $variant;
    }
}

// Additional modifiers
$modifierClasses = '';
if ($dashed && (str_contains($variantClasses, 'border') || $outline)) {
    $modifierClasses .= ' border-dashed';
} elseif ($dashed) {
    $modifierClasses .= ' border border-dashed border-gray-300';
}

if ($underline) {
    $modifierClasses .= ' underline hover:no-underline';
}

if ($block) {
    $modifierClasses .= ' w-full';
}

// Loading and disabled states
$stateClasses = '';
$cursorClasses = ' cursor-pointer ';
if ($disabled || $loading) {
    $stateClasses .= ' opacity-50 pointer-events-none';
    $cursorClasses = ' cursor-not-allowed ';
}

// Combine all classes
$allClasses = trim(
    $baseClasses .
        ' ' .
        $variantClasses .
        ' ' .
        $sizeClasses .
        ' ' .
        $modifierClasses .
        ' ' .
        $stateClasses .
        ' ' .
        $cursorClasses .
        ' flex items-center justify-center gap-3 ',
);

// Icon size based on button size - using array approach
$iconSizes = [
    'sm' => 'size-4',
    'md' => 'size-5',
    'lg' => 'size-6',
    'xl' => 'size-7',
];
$iconSize = $iconSizes[$size] ?? $iconSizes['md'];

// Loading spinner size based on button size
$spinnerSizes = [
    'sm' => 'size-4',
    'md' => 'size-5',
    'lg' => 'size-6',
    'xl' => 'size-7',
];
$spinnerSize = $spinnerSizes[$size] ?? $spinnerSizes['md'];

// Icon margin classes
$iconMarginLeft = $iconPosition === 'left' ? 'mr-2' : '';
$iconMarginRight = $iconPosition === 'right' ? 'ml-2' : '';
$iconMarginClass = $iconMarginLeft . $iconMarginRight;

// Determine wire:navigate usage
$shouldUseWireNavigate =
    $wireNavigate &&
    $isLink &&
    (!empty($route) ||
        (!empty($href) &&
            !str_starts_with($href, 'http') &&
            !str_starts_with($href, 'mailto:') &&
            !str_starts_with($href, 'tel:')));

// Prepare final attributes
$elementAttributes = [];

if ($isLink) {
    $elementAttributes['href'] = $finalHref ?: '#';
    $elementAttributes['target'] = $target;

    if ($shouldUseWireNavigate) {
        $elementAttributes['wire:navigate'] = true;
    }
} else {
    $elementAttributes['type'] = $type;

    if ($disabled || $loading) {
        $elementAttributes['disabled'] = true;
    }
}

if ($action) {
    $elementAttributes['wire:click'] = $action;
}

$elementAttributes['class'] = $allClasses;
@endphp

@if ($isLink)
    <a {{ $attributes->merge($elementAttributes) }}>
        @if ($loading && $action)
            <span wire:loading wire:target="{{ $action }}"
                class="absolute inset-0 flex items-center justify-center">
                {{-- <flux:icon.loading class="{{ $spinnerSize }}" /> --}}
            </span>
        @endif

        <span
            class="flex items-center justify-center gap-3 {{ $loading && $action ? 'wire:loading.class=opacity-0' : '' }}"
            @if ($action) wire:target="{{ $action }}" @endif>

            @if ($icon && $iconPosition === 'left')
                {{-- <flux:icon name="{{ $icon }}" class="{{ $iconMarginClass }} {{ $iconSize }} {{ $iconClasses }}" /> --}}
            @endif

            {{ $slot }}

            @if ($icon && $iconPosition === 'right')
                {{-- <flux:icon name="{{ $icon }}" class="{{ $iconMarginClass }} {{ $iconSize }} {{ $iconClasses }}" /> --}}
            @endif
        </span>
    </a>
@else
    <button {{ $attributes->merge($elementAttributes) }}>
        @if ($loading || $action)
            <span wire:loading @if ($action) wire:target="{{ $action }}" @endif
                class="absolute inset-0 flex items-center justify-center">
                {{-- <flux:icon.loading class="{{ $spinnerSize }}" /> --}}
            </span>
        @endif

        <span
            class="flex items-center justify-center gap-3 {{ $loading || $action ? 'wire:loading.class=opacity-0' : '' }}"
            @if ($action) wire:target="{{ $action }}" @endif>

            @if ($icon && $iconPosition === 'left')
                {{-- <flux:icon name="{{ $icon }}" class="{{ $iconMarginClass }} {{ $iconSize }} {{ $iconClasses }}" /> --}}
            @endif

            {{ $slot }}

            @if ($icon && $iconPosition === 'right')
                {{-- <flux:icon name="{{ $icon }}" class="{{ $iconMarginClass }} {{ $iconSize }} {{ $iconClasses }}" /> --}}
            @endif
        </span>
    </button>
@endif
