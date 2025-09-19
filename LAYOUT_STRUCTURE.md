# Layout Structure Documentation

## Overview

The LMS application uses a component-based layout system with Blade components for maximum reusability and maintainability.

## Key Layout Components

### `x-app-layout`

Located at: `resources/views/components/app-layout.blade.php`

This is the main layout component that provides:

-   Base HTML structure
-   Meta tags and CSRF token
-   Font loading (Inter from Bunny Fonts)
-   Asset loading via Vite
-   Navigation bar (desktop and mobile)
-   User menu
-   Breadcrumbs support
-   Flash message handling (success/error)
-   Alpine.js integration

### Usage Example

```blade
<x-app-layout>
    <x-slot name="header">
        <h1>Page Title</h1>
    </x-slot>

    <!-- Main content -->
    <div>
        Content goes here
    </div>
</x-app-layout>
```

### Props

-   `breadcrumbs` (optional): Array of breadcrumb items
-   `header` (optional): Content for the page header

### Slots

-   `header`: For page header content
-   Default slot: For main page content

## Implementation Notes

1. The layout is implemented as a single component without nested layouts to prevent duplication
2. Alpine.js is loaded via Vite bundle, not via CDN
3. Tailwind classes are used for styling
4. The layout is responsive with a mobile menu
5. Session flash messages ('success' and 'error') are automatically displayed

## Common Gotchas

1. Do not include Alpine.js via CDN - it's already bundled via Vite
2. Don't create nested layouts - use the main app-layout component
3. Always use `@vite` for asset loading

## Best Practices

1. Use slots for page-specific content
2. Use the header slot for consistent page headers
3. Follow the breadcrumb structure for navigation
4. Utilize the flash message system for user feedback
