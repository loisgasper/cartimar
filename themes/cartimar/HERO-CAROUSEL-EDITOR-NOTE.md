# Hero Carousel: editor preview vs. live site

**What works:** editing a slide (uploading/replacing an image or video, reordering, adding/removing slides) always updates the live site correctly. The List View panel (left sidebar in the block editor) also shows a correct thumbnail preview of every slide.

**Known limitation:** in the main block editor canvas, the hero carousel's slide images/video don't render visually the way they do on the live site — the hero area shows its dark background color instead of the actual photo/video. Arrows and dots do appear and are clickable.

**Why:** the hero carousel's slide-switching (crossfade, autoplay, arrows, dots) is driven by JavaScript that only runs on the front end. We added a version of that script for the editor too, and it correctly tracks which slide is "active" — but WordPress's own block editor applies an inline `position: relative` style to each image/video block for its own resize-handle UI, which can't be overridden by the theme's CSS (inline styles always win). That breaks the layout technique the carousel uses to stack slides on top of each other in the editor specifically. This does not affect the live site at all, which doesn't have that inline style.

**How to check what a slide looks like while editing:** use the List View panel's thumbnails, or save/preview the page to see it rendered as visitors will see it.

**If revisiting this:** the fix likely needs either (a) a different CSS layout technique that isn't defeated by an inline `position: relative`, or (b) a client-side workaround in `hero-carousel-editor.js` that reads each slide's `src`/attachment and paints it as a `background-image` on a plain, non-block-managed `<div>` instead of relying on the actual `<img>`/`<video>` elements' layout.

Relevant files:
- `assets/css/main.css` — `.cart-hero__slides` rules (search "HERO" section)
- `assets/js/hero-carousel-editor.js` — editor-only carousel script
- `functions.php` — `cartimar_enqueue_editor_hero_carousel()`
