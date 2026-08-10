---
name: Agro-Modernist Grid
colors:
  surface: '#f8faf8'
  surface-dim: '#d8dad9'
  surface-bright: '#f8faf8'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f2f4f2'
  surface-container: '#eceeec'
  surface-container-high: '#e6e9e7'
  surface-container-highest: '#e1e3e1'
  on-surface: '#191c1b'
  on-surface-variant: '#41493e'
  inverse-surface: '#2e3130'
  inverse-on-surface: '#eff1ef'
  outline: '#717a6d'
  outline-variant: '#c0c9bb'
  surface-tint: '#2a6b2c'
  primary: '#00450d'
  on-primary: '#ffffff'
  primary-container: '#1b5e20'
  on-primary-container: '#90d689'
  inverse-primary: '#91d78a'
  secondary: '#835400'
  on-secondary: '#ffffff'
  secondary-container: '#fcab28'
  on-secondary-container: '#694300'
  tertiary: '#4d352b'
  on-tertiary: '#ffffff'
  tertiary-container: '#674b41'
  on-tertiary-container: '#e2bdb0'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#acf4a4'
  primary-fixed-dim: '#91d78a'
  on-primary-fixed: '#002203'
  on-primary-fixed-variant: '#0c5216'
  secondary-fixed: '#ffddb5'
  secondary-fixed-dim: '#ffb957'
  on-secondary-fixed: '#2a1800'
  on-secondary-fixed-variant: '#643f00'
  tertiary-fixed: '#ffdbce'
  tertiary-fixed-dim: '#e4beb2'
  on-tertiary-fixed: '#2b160f'
  on-tertiary-fixed-variant: '#5b4137'
  background: '#f8faf8'
  on-background: '#191c1b'
  surface-variant: '#e1e3e1'
  orange-money: '#FF8200'
  mtn-money: '#FFCC00'
  moov-money: '#0062A9'
  status-success: '#2E7D32'
  status-error: '#D32F2F'
  status-info: '#0277BD'
typography:
  headline-lg:
    fontFamily: Hanken Grotesk
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
    letterSpacing: -0.02em
  headline-lg-mobile:
    fontFamily: Hanken Grotesk
    fontSize: 24px
    fontWeight: '700'
    lineHeight: 32px
  headline-md:
    fontFamily: Hanken Grotesk
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  body-lg:
    fontFamily: Hanken Grotesk
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Hanken Grotesk
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-md-bold:
    fontFamily: Hanken Grotesk
    fontSize: 16px
    fontWeight: '600'
    lineHeight: 24px
  label-sm:
    fontFamily: JetBrains Mono
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
    letterSpacing: 0.05em
  label-caps:
    fontFamily: Hanken Grotesk
    fontSize: 12px
    fontWeight: '700'
    lineHeight: 16px
    letterSpacing: 0.1em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 8px
  gutter: 16px
  margin-mobile: 16px
  margin-desktop: 32px
  card-padding: 20px
  stack-gap: 12px
---

## Brand & Style

The design system is built on a foundation of **Modern Agriculturalism**. It bridges the gap between the grounded, earthy reality of poultry farming and the high-efficiency world of digital logistics. The visual language evokes feelings of growth, reliability, and precision.

The chosen style is **Corporate / Modern** with a **Tactile** edge. It utilizes a clean, card-based architecture that prioritizes information density and legibility. While the structure is professional and grid-aligned, the use of soft shadows and generous rounded corners ensures the interface remains approachable for local farmers and delivery personnel. High contrast and a vibrant, nature-inspired palette ensure usability even in high-glare outdoor environments typical of agricultural work.

## Colors

The palette is rooted in the natural world. **Forest Green** serves as the primary brand anchor, symbolizing growth and the agricultural heart of the product. **Solar Yellow** is used strategically for high-visibility secondary actions and highlights, evoking energy and fresh produce. **Earth Brown** is reserved for subtle accents, grounding the design in the literal "land."

Backgrounds utilize a very light, cool-toned gray to reduce eye strain while maintaining a crisp, professional aesthetic. For payment-specific flows, the system adopts the rigid brand colors of mobile money providers to ensure instant recognition and trust during transactions.

## Typography

This design system uses **Hanken Grotesk** as the primary typeface. Its sharp, contemporary geometry provides a professional and precise feel, while its high legibility makes it perfect for functional marketplace data. Headlines use a tight letter-spacing and heavy weights to command authority.

For technical metadata—such as Order IDs, coordinates, and weight measurements—**JetBrains Mono** is employed. This monospaced choice ensures that numerical data is easy to scan and compare in list views. The type hierarchy is designed to be "glanceable," prioritizing price and location information through weight and scale.

## Layout & Spacing

The system follows an **8px grid** for vertical rhythm and a **12-column fluid grid** for desktop layouts. On mobile devices, the layout shifts to a single-column stack with standardized 16px side margins to ensure content remains readable and buttons are easy to tap with a single thumb.

Spacing is used to create clear logical groupings. Components like farm listings use a "stack-gap" of 12px to keep related information tight, while larger sections use multiples of the 16px gutter to provide breathing room. The "card-padding" is intentionally generous (20px) to prevent the UI from feeling cramped.

## Elevation & Depth

Visual hierarchy is achieved through **Tonal Layering** and **Ambient Shadows**. The background is kept flat (`neutral-color-hex`), while interactive cards and containers sit on a primary elevation layer.

Shadows are soft, diffused, and slightly tinted with the primary green to feel integrated rather than "pasted on." 
- **Low Elevation:** Used for standard product and farmer cards (4px blur, 0.05 opacity).
- **High Elevation:** Used for Floating Action Buttons (FABs) and real-time tracking overlays on maps (12px blur, 0.1 opacity).
- **Focus State:** Elements being interacted with (like a selected order) use a 2px solid primary border instead of increased shadow to maintain clarity.

## Shapes

The shape language is consistently **Rounded**. Standard UI elements like input fields and buttons utilize a 0.5rem (8px) radius. Larger containers, such as product cards and farmer profiles, use a 1rem (16px) radius to create a soft, friendly silhouette. Avatars and status indicators use full pill-shapes or circles to differentiate them from structural layout components.

## Components

### Buttons
Buttons are large and high-contrast. **Primary CTAs** use a solid Forest Green background with white text. **Secondary CTAs** (e.g., "View Map") use Solar Yellow to draw attention without competing with the primary flow. All buttons have a minimum height of 48px for outdoor tap-friendliness.

### Cards
Cards are the core of the marketplace. They feature a white surface, rounded corners (16px), and a subtle ambient shadow. Farmer cards include an avatar, a star rating, and a location label. Poultry listing cards prioritize the photo and price-per-kg in bold.

### Status Indicators
Delivery and order tracking use pill-shaped badges. 
- `In Transit`: Solar Yellow background with dark text.
- `Delivered`: Success Green background with white text.
- `Cancelled`: Error Red background with white text.

### Input Fields
Forms for farmer registration use high-contrast outlines (2px) and include explicit labels. Placeholder text is low-contrast, but once typed, text uses `body-md-bold` for maximum clarity.

### Map Markers & Overlays
Map elements use `status-info` blue for routes and the primary brand green for farm locations. Information "drawers" that slide up from the bottom of the map use a 24px top-corner radius to signal they are "tucked" into the bottom of the screen.