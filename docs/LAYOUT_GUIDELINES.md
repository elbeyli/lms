# Layout Components & Styling Guidelines

## Layout System

### Component Structure

-   Use `x-app-layout` as the main layout wrapper
-   Keep layout logic centralized in app-layout.blade.php
-   Avoid nested layouts to prevent duplication

### Asset Management

-   Use Vite for all asset bundling
-   Prefer bundled packages over CDN links
-   Run `npm run build` after layout changes

### Component Best Practices

-   Use slots for content injection
-   Leverage named slots for specific sections
-   Follow established page structure patterns

## Styling Guidelines

### CSS Management

-   Use Tailwind utility classes
-   Follow mobile-first responsive design
-   Maintain consistent spacing and sizing

### JavaScript Integration

-   Use Vite for JavaScript bundling
-   Leverage Alpine.js for interactivity
-   Follow established component patterns

## Testing

-   Test layout rendering
-   Verify responsive behavior
-   Check asset loading
-   Validate JavaScript functionality
