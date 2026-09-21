---
name: antislop-layoutmobile
description: "Mobile layout skill for antislop. Breakpoints, grids, overflow, tap targets. Load with the core."
allowed-tools: Read Write Edit Glob Grep
---
# antislop-layoutmobile

> Anti Slop: Rules for AI Coding Agents. Mobile Layout skill

> Part of the antislop system. Read together with `antislop.md` (the core). This skill deep-dives the responsive layout concern: how a layout must reflow across screen sizes. It references core rules by number and never duplicates or renumbers them.

## How to use this skill

- Load together with `antislop.md` whenever the task is mobile or responsive layout work.
- The principle: **mobile layout is a different layout, not the desktop layout at a smaller size.**

## Breakpoints

### Desktop-Only Layout

- **Tell:** one layout state for every screen; mobile is desktop squeezed into a phone.
- **Why:** R-03 requires a mobile layout that is perfect, not an afterthought.
- **Fix:** define a real mobile state where content stops working. Columns stack, sizes drop, order changes.

### Two-State Layout

- **Tell:** exactly two states: single stacked column below one breakpoint, wide multi-column above it.
- **Why:** leaves the 600-1024px range (tablets, small laptops) broken.
- **Fix:** define real states at widths where content stops working. Typically three states: single, two-column, full multi-column.

## Scale & Sizing

### Desktop-Sized Everything

- **Tell:** padding, gaps, hero heights unchanged from desktop to mobile.
- **Why:** elements sized for 1440px dominate 375px screens.
- **Fix:** give mobile its own size step: smaller type, tighter padding, smaller gaps.

### Fixed Pixel Type

- **Tell:** font sizes in fixed px that never change between desktop and mobile.
- **Why:** type that does not respond to viewport is type sized for one screen.
- **Fix:** use fluid type (`clamp()`) or set smaller type at breakpoint.

### 100vh Sections

- **Tell:** hero/section heights set to `100vh`, filling the whole phone screen.
- **Why:** overflows visible area on mobile browsers; dominates layout.
- **Fix:** let sections size to content (`auto`) or use `dvh`.

## Grids & Stacking

### Columns That Don't Collapse

- **Tell:** multi-column grid keeps side-by-side columns on mobile, elements collide.
- **Why:** grid narrows but does not re-stack.
- **Fix:** collapse to single column at breakpoint. Side-by-side becomes stacked.

## Overflow

### Horizontal Scroll Leak

- **Tell:** page scrolls sideways because some element is wider than viewport.
- **Why:** horizontal scrolling is a broken promise on mobile (R-03).
- **Fix:** find the offending element, contain or reflow it. Verify zero horizontal scroll at narrowest target.

### Overflow Hidden Clipping

- **Tell:** `overflow: hidden` clips content at narrow widths.
- **Why:** clipping hides a failure. User loses information.
- **Fix:** let content reflow instead of clipping.

## Tap Targets

### Under-Sized Targets

- **Tell:** buttons/links smaller than 44x44px.
- **Why:** thumbs lack pixel accuracy; fine on desktop, frustrating on phone.
- **Fix:** minimum touch area 44x44px using padding or larger hit box.

### Targets Too Close

- **Tell:** 44px targets packed together with no gap.
- **Why:** two large controls touching behave like one large control.
- **Fix:** leave clear gap between adjacent interactive targets.

### Hover-Only Interactions

- **Tell:** menus/reveals that exist only on hover.
- **Why:** no hover on touchscreen. Interaction does not exist for mobile users.
- **Fix:** give every hover-only interaction a tap equivalent.

## Mobile Navigation

### Nav That Stays Desktop

- **Tell:** desktop top bar with row of links kept side by side on mobile.
- **Why:** links crowd, wrap, or spill past viewport on phone.
- **Fix:** collapse nav into mobile pattern at breakpoint: bottom nav or menu.

### Bottom Nav That Eats Content

- **Tell:** fixed bottom nav bar sits over content, covering last items.
- **Why:** fixed bar takes real space on small screen.
- **Fix:** reserve bar height with scroll padding and safe-area insets.

## Layoutmobile Skill Checklist

- [ ] Layout reflows into distinct mobile state (R-03)
- [ ] Defined states across width range, not just phone/desktop (R-03, R-35)
- [ ] Sizes use mobile scale, not desktop unchanged (R-03, R-05)
- [ ] Multi-column grids collapse and stack (R-03)
- [ ] No horizontal overflow, nothing clipped (R-03)
- [ ] Interactive targets at least 44x44px with spacing (R-03)
- [ ] Hover-only interactions have tap equivalent (R-03)
- [ ] Navigation reflows into mobile pattern (R-03)
- [ ] Fixed nav bars never cover content (R-03)
- [ ] Layout verified at mobile breakpoints (R-35)
