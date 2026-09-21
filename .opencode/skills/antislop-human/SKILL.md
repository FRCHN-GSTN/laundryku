---
name: antislop-human
description: "Human and accessibility skill for antislop. Contrast, keyboard, focus, and states for real people."
allowed-tools: Read Write Edit Glob Grep Bash
---
# antislop-human

> Anti Slop: Rules for AI Coding Agents. Human skill

> Part of the antislop system. Read together with `antislop.md` (the core). This skill deep-dives the human concern: the UI must stay usable by people with different eyes, hands, and setups. Contrast, keyboard, focus, states, and the mobile details that exclude people.

## How to use this skill

- Load together with `antislop.md` whenever the task builds or edits UI. The core holds the mechanism; this skill holds the human-side depth.
- Every entry has the same shape: **Tell** (the pattern), **Why** (who it excludes), **Fix** (what to do instead), with the governing core rule cited as R-XX.

## Color & Contrast

### Low-Contrast Text

- **Tell:** light grey text on white/near-white, muted labels chosen for elegance over readability.
- **Why:** excludes low-vision users and everyone in bright light.
- **Fix:** meet WCAG AA minimums (R-25): 4.5:1 for normal text, 3:1 for large text (18px+).

### Text Over a Photo or Gradient

- **Tell:** white text over an image/gradient that is light in some areas, checked at one spot only.
- **Why:** contrast is local. Where the image is light, text drops below 4.5:1.
- **Fix:** add a scrim or solid color block behind text, verify the worst spot.

### Non-Text Contrast

- **Tell:** interactive components distinguished from background by less than 3:1.
- **Why:** low-vision users cannot find controls when edges are faint.
- **Fix:** give every component boundary a 3:1 ratio against adjacent colors (WCAG 1.4.11).

## Keyboard

### Removed Focus Outline

- **Tell:** `outline: none` or `outline: 0` with no replacement.
- **Why:** keyboard users cannot see where they are. R-32 forbids this outright.
- **Fix:** replace with a visible `:focus-visible` style meeting 3:1 contrast.

### Mouse-Only Patterns

- **Tell:** menus opening on hover only, drag-and-drop with no keyboard fallback.
- **Why:** excludes keyboard and assistive-technology users (R-32).
- **Fix:** every interactive element reachable and operable by keyboard.

## Focus & States

### Weak or Invisible Focus Indicator

- **Tell:** focus ring same color as background, or only on hover.
- **Why:** keyboard-only use breaks without visible focus (R-32, R-34).
- **Fix:** visible focus indicator, 3:1 contrast, in every theme.

### Color-Only Feedback

- **Tell:** success/error/status communicated only by color, no icon or label.
- **Why:** excludes color-blind users; disappears in forced-colors mode.
- **Fix:** pair every color signal with text, an icon, or a pattern.

### Missing UI States

- **Tell:** data view with no empty, loading, or error state.
- **Why:** R-27 requires all three; each must be perceivable and informative.
- **Fix:** every data view has explicit empty, loading, and error states.

## Human Skill Checklist

- [ ] Every text/background pairing verified against contrast standards (R-25)
- [ ] Interactive component boundaries meet 3:1 against background
- [ ] Focus indicator visible, high-contrast, present on every element in every theme (R-32, R-34)
- [ ] Every interactive element reachable/operable by keyboard (R-32, R-26)
- [ ] Empty, loading, error states present and perceivable (R-27, C-4)
