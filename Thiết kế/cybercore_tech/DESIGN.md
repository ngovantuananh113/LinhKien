```markdown
# Design System Document

## 1. Overview & Creative North Star: "The Synthetic Architect"

This design system is not a template; it is a high-performance engine. Moving away from the "soft and friendly" web, this system embraces **The Synthetic Architect**—a North Star that prioritizes precision, mathematical beauty, and the raw energy of high-end computer hardware.

We break the "standard" layout through **Intentional Asymmetry** and **Circuit-Inspired Geometry**. The interface should feel like a custom-built machine: cold, sharp, and hyper-functional, yet electrified by neon light. We avoid traditional grids in favor of overlapping panels, sharp 0px corners, and depth created through luminosity rather than physics.

---

## 2. Colors: The Neon Spectrum

Our palette is anchored in the void of `surface` (#131313) and brought to life through high-energy accents.

### The Palette Roles
*   **Primary (Neon Purple):** Use `primary` (#d2bbff) for high-frequency interactions and `primary_container` (#7b2ff7) for the core brand energy.
*   **Secondary (Electric Cyan):** Use `secondary` (#e6feff) and `secondary_container` (#00f4fe) to signify technical specs, cooling, or "live" states.
*   **Surface Hierarchy:** We utilize `surface_container_lowest` through `highest` to create a "nested" tech stack.

### The "No-Line" Rule
**Prohibit 1px solid borders for sectioning.** Boundaries must be defined solely through background shifts. To separate a product gallery from a spec sheet, transition from `surface` to `surface_container_low`. The only "lines" allowed are functional circuit-paths or "Ghost Borders" (see Elevation).

### Glass & Gradient Rule
To achieve "visual soul," use linear gradients (45-degree angle) transitioning from `primary_container` to `secondary_container`. Apply these to hero backgrounds or main CTAs. Floating panels should utilize `surface_variant` at 60% opacity with a `20px` backdrop blur to simulate frosted acrylic.

---

## 3. Typography: Tech-Sharp Legibility

We pair two high-contrast typefaces to balance "Engineering" with "Editorial."

*   **Display & Headlines (Space Grotesk):** A sharp, geometric sans-serif. Use `display-lg` (3.5rem) for product names and `headline-lg` (2rem) for category headers. These should feel authoritative and architectural.
*   **Body & Titles (Manrope):** A modern, high-legibility sans-serif. `body-md` (0.875rem) handles the technical specifications and descriptions.
*   **The Hierarchy:** Use extreme scale differences. A `display-lg` headline should sit near a `label-sm` technical tag to create a sense of sophisticated data density.

---

## 4. Elevation & Depth: Tonal Layering

Traditional shadows have no place here. We replace "soft light" with "digital glow."

*   **The Layering Principle:** Stack `surface-container-highest` components (like a GPU spec card) over `surface-container-low` sections. This creates a natural "lift" without relying on outdated drop shadows.
*   **Ambient Glows:** When an element must "float," use a glow instead of a shadow. Apply a diffused blur (20px+) of the `primary` or `secondary` color at 10-15% opacity behind the element.
*   **The Ghost Border Fallback:** If a container requires definition against a similar background, use a `1px` border of `outline_variant` at **20% opacity**.
*   **Angular Cutouts:** All containers must maintain a `0px` border radius. To add complexity, use CSS clip-paths to create "chamfered" corners (45-degree angles) on the top-right and bottom-left of major containers.

---

## 5. Components: Precision Engineering

### Buttons: The "Power Cell"
*   **Primary:** Sharp 0px corners. Background: Gradient from `primary_container` to `secondary_container`. Border: `1px` solid `secondary_fixed`. Effect: Subtle outer glow of `secondary_container`.
*   **Secondary:** Ghost style. Transparent background with a `1px` border of `primary`. On hover, the background fills with `surface_container_highest`.

### Cards: Hardware Modules
*   **Style:** Forbid divider lines. Use `1.5rem` (spacing-6) of vertical white space to separate the image from the metadata.
*   **Interaction:** On hover, the card should scale (1.02x) and the border should transition from `outline_variant` (20%) to a vibrant `secondary` (100%) glow.

### Input Fields: Command Lines
*   **Style:** Bottom-border only (`2px` solid `outline_variant`). 
*   **Focus State:** The bottom border transforms into a `secondary` (Cyan) line with a small "active" glow. Text should be `secondary_fixed` for high contrast.

### Technical Chips
*   **Style:** Small, rectangular tags using `surface_container_highest` and `label-md` typography. Used for "In Stock" or "RTX Ready" indicators.

---

## 6. Do's and Don'ts

### Do:
*   **Embrace the Void:** Use `surface_container_lowest` (#0e0e0e) for large background areas to let the neon accents pop.
*   **Asymmetric Layouts:** Offset images of hardware so they "break" the container edges.
*   **Data Density:** Treat the UI like a dashboard. It's okay to show more info if it's organized with clear `surface` shifts.

### Don't:
*   **No Rounded Corners:** Never use `border-radius`. Every element must be sharp (`0px`).
*   **No Generic Grays:** Avoid neutral grays. All "blacks" should be tinted with the `surface` palette to maintain the "Dark Graphite" feel.
*   **No Standard Dividers:** Never use a horizontal rule `<hr>`. Use space or a change in `surface_container` color.
*   **No "Soft" Animations:** Use "Power 4" or "Expo" easing functions for transitions—animations should feel fast, snappy, and robotic, not "bouncy" or "playful."