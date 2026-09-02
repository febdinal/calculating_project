# Hallmark Design System & Anti-AI-Slop Guidelines (Theme: Tally)

## Philosophy & Core Principles

Hallmark is an opinionated anti-AI-slop design system that produces websites that look human-crafted, tactile, and mathematically refined rather than generic AI output.

### Non-Negotiable Anti-AI-Slop Rules
1. **No Generic Purple/Pink Rainbow Slop Everywhere**:
   - Use curated, intentional color tokens (OKLCH palette with deep slate paper, crisp hairline rules, cool indigo accents, and subtle lime/emerald companions).
2. **Structural & Geometric Variety**:
   - Layouts use bento grids, tactile cards with hairline borders (`1px solid rgba(255,255,255,0.08)`), floating pill navigation (`N5 Floating Pill`), and refined metering benches.
3. **Typography Purity**:
   - Primary Display & Body: `Geist` / `Plus Jakarta Sans`
   - Monospace: `Geist Mono` / `JetBrains Mono`
   - Italic Accent: `Instrument Serif` (used exclusively for tasteful contrast, never for all headers).
   - Headings are upright and roman (`font-style: normal`).
4. **Real Micro-Interactions & Tactile States**:
   - Interactive components ship with distinct states: `default`, `hover`, `:focus-visible`, `:active` (`transform: translateY(1px)`).
   - Easing: `cubic-bezier(0.22, 0.61, 0.36, 1)`.
5. **Locked Tokens**:
   - Consistent typography scales, radiuses (`radius-pill: 999px`, `radius-2xl: 1rem`), and hairline borders.
6. **Zero Re-Drawn Fake Chrome**:
   - Avoid fake browser window title bars with traffic light dots unless specifically needed for screenshots.

---

## Tally Theme Tokens Reference

```css
:root {
  /* Paper / Ink */
  --tally-paper-0: #0a0e1a;
  --tally-paper-1: #0f172a;
  --tally-paper-2: #1e293b;
  --tally-paper-3: #334155;
  
  --tally-ink-0: #f8fafc;
  --tally-ink-1: #cbd5e1;
  --tally-ink-2: #94a3b8;
  --tally-ink-3: #64748b;

  /* Accents */
  --tally-accent: #6366f1;
  --tally-accent-soft: #818cf8;
  --tally-accent-tint: rgba(99, 102, 241, 0.12);
  --tally-companion: #10b981; /* Live green */
  --tally-rose: #f43f5e;     /* Custom pink-rose */

  /* Hairlines */
  --tally-hairline: 1px solid rgba(255, 255, 255, 0.08);
  --tally-hairline-soft: 1px solid rgba(255, 255, 255, 0.14);

  /* Fonts */
  --font-display: "Geist", "Plus Jakarta Sans", sans-serif;
  --font-body: "Geist", "Plus Jakarta Sans", sans-serif;
  --font-mono: "Geist Mono", "JetBrains Mono", monospace;
  --font-italic: "Instrument Serif", serif;
}
```
