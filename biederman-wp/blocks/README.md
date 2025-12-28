# Custom Gutenberg Blocks

This directory contains custom Gutenberg blocks for the Biederman theme.

## Blocks

- **Show Featured Block** (`biederman/show-featured`) - Displays the featured show (or next upcoming show)
- **Show List Block** (`biederman/show-list`) - Displays a list of shows from the Shows custom post type
- **Press Assets Block** (`biederman/press-assets`) - Displays press assets from the Press Assets custom post type

## Building the Blocks

The blocks use `@wordpress/scripts` for building. To build the blocks:

```bash
npm install
npm run build
```

For development with watch mode:

```bash
npm start
```

## Block Structure

Each block follows this structure:

```
blocks/
  show-featured/
    block.json          # Block metadata
    render.php          # Server-side rendering
    src/
      index.js         # Editor component (React)
      index.css        # Editor styles
      style-index.css  # Frontend styles
    build/             # Built files (generated)
  show-list/
    block.json          # Block metadata
    render.php          # Server-side rendering
    src/
      index.js         # Editor component (React)
      index.css        # Editor styles
      style-index.css  # Frontend styles
    build/             # Built files (generated)
```

## Usage in Templates

Use the blocks in your templates or block editor:

```html
<!-- Show Featured Block -->
<!-- wp:biederman/show-featured /-->

<!-- Show List Block -->
<!-- wp:biederman/show-list {"limit":5} /-->

<!-- Press Assets Block -->
<!-- wp:biederman/press-assets {"type":"photo","limit":-1} /-->
```

## Block Attributes

### Show Featured Block
- No attributes (automatically displays featured or next upcoming show)

### Show List Block
- `limit` (number, default: 5) - Number of shows to display

### Press Assets Block
- `type` (string, default: "") - Filter by press asset type (e.g., "photo", "rider", "logo")
- `limit` (number, default: -1) - Number of assets to display (-1 for all)

