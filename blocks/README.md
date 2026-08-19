# Responsive Menu — block editor

Two blocks ship here:

| Block | Role |
| --- | --- |
| `rmp/menu` | The `nav`: the hamburger trigger, the off-canvas panel, and everything about how the menu opens, closes and switches to a desktop bar. |
| `rmp/menu-items` | The list of links inside it, plus the styling of items, sub-menus and the desktop dropdowns. Only valid inside `rmp/menu`. |

Anything can go in the panel — headings, a search block, social links, images —
because the panel is an `InnerBlocks` region. `rmp/menu-items` is just one of the
things you can put in it.

## Working on it

```bash
cd blocks
npm install
npm run start          # watch build
npm run build          # production build — commit the result, it ships
npm run test:unit      # jest (jsdom) over the responsive model and the runtime
php tests/block-css-test.php   # the server-side CSS generation and sanitiser
```

`blocks/build/` is committed on purpose: WordPress.org installs the plugin from
the repository, so an un-rebuilt bundle ships stale code. Rebuild before you
commit any change under `blocks/src/`.

## How responsive styling works

Three ideas, and everything else follows from them.

**1. Every visual setting is a CSS custom property.** `src/styles.js` turns the
block attributes into a `--rmp--*` bag; `src/style.scss` never hard-codes a
value, it only reads those properties. That is what makes per-device styling
cheap — the same stylesheet serves every viewport, and only the property values
change.

**2. Desktop values live on the attributes; tablet and mobile hold only what
differs.** `src/utils/responsive.js` owns this. A menu saved before the feature
existed is already a valid desktop-only menu, so nothing had to be migrated.

```
attributes.menuStyle                     → desktop
attributes.responsive.tablet.menuStyle   → tablet overrides
attributes.responsive.mobile.menuStyle   → mobile overrides
```

A device inherits from the next widest one, so an untouched tablet looks exactly
like desktop until something is explicitly overridden. In the inspector an
overridden control gets a coloured rail and a reset button — that reset is the
only way back to inheriting once a value has been touched.

**Only settings that end up as a custom property can vary by device.** Anything
that changes the saved markup or a class name — the trigger type, its icon or
image, the labels, the animation, the sub-menu arrow, the indentation side — has
a single saved value, so the inspector writes it through `createSharedHelpers`
whatever device is selected. Showing those per-device would mean the editor
rendering a change that `save` then drops on the floor. If you add a control,
decide which of the two it is before wiring it up.

**3. The three tiers become one rule and two media queries.** The editor saves
`blockStyles` as `{ base, tablet, mobile }`, where the two narrower tiers carry
only the properties that changed. `blocks/block.php` re-emits them as:

```css
.rmp-block-navigator-ID { …base… }
@media (max-width: 1024px) { .rmp-block-navigator-ID { …tablet… } }
@media (max-width:  767px) { .rmp-block-navigator-ID { …mobile… } }
```

At mobile width both queries match, so the mobile tier inherits from tablet
through the cascade — which is exactly why storing a diff is enough.

Values arrive from post content, so PHP treats them as untrusted: only
`--rmp--*` property names are allowed through, and values are stripped of
anything that could close the declaration, plus `expression()`, `@import` and
non-URL `url()`. `tests/block-css-test.php` pins all of that down.

### Breakpoints

Three, and they mean different things:

- **Collapse to hamburger below** — the layout switch. Above it the menu is an
  inline bar; below it, an off-canvas panel behind the hamburger. **0 means
  never**: the menu stays off-canvas at every width. That value has to survive
  as 0 rather than be read as "unset" — defaulting it would make the generated
  CSS force the menu open at widths where the runtime keeps it closed.
- **Tablet styles below** / **Mobile styles below** — the styling tiers above.
  Mobile is clamped below tablet at render time.

The menu provides all three as block context, so `rmp/menu-items` renders its
own media queries against the same numbers without duplicating the settings.

## The editor preview

The canvas renders the same `TriggerContent` component the frontend saves, with
the selected device's *resolved* properties applied inline, and picks up
`.rmp-desktop-mode` when the previewed width is above the breakpoint. So the
editor shows the real markup with the real styles for the device you are
editing, including the switch to the desktop bar.

Two toolbar controls drive it: the device switcher (desktop / tablet / mobile,
shared by both blocks via `src/utils/device-store.js` — it is an editing
preference, not content, so it never dirties the post) and an open/closed
toggle. With the menu "closed" the panel still renders in flow, because
otherwise the inner blocks could not be edited at all.

## The frontend runtime

`src/view.js` boots `src/frontend/menu.js` for every `.rmp-block-navigator`, and
keeps watching for menus injected later — AJAX headers and page builders add
navigation after `DOMContentLoaded`, and a one-shot init leaves those dead.

`ResponsiveMenu` owns the open state, the desktop switch and every close
trigger; `SubmenuController` owns one menu list. Points worth knowing:

- Close handlers are bound **once** and check the open state, rather than being
  attached per open — that is what stops listeners stacking on repeated toggles.
- A closed panel is `visibility: hidden`, delayed by the animation duration, so
  it finishes sliding out before leaving the tab order. A closed menu is never a
  set of invisible focusable links.
- The trigger carries `aria-expanded`; the panel is `aria-hidden` while closed;
  Escape closes; Tab is trapped inside an open panel; focus returns to the
  trigger on close.
- Sub-menu arrows are built with DOM APIs, never `innerHTML`. Icon arrows are
  cloned from a hidden template the block prints, because an SVG cannot be
  reconstructed from an attribute.
- `blocks/block.php` emits a small `min-width` media query per menu so a wide
  viewport paints the desktop layout before the script has run. The full desktop
  styling still comes from `.rmp-desktop-mode` in the stylesheet.

Menus fire `rmp-menu-open` / `rmp-menu-close` on the `nav`, and
`window.rmpBlockMenu.get(navElement)` returns the instance, so themes can drive
a menu without reaching into the DOM.

## Extending the list of menu items

`MENU_ITEM_BLOCKS` (`src/menu-items/constants.js`, mirrored in `block.php`) is
what the container admits through `allowedBlocks`. The `blocks.registerBlockType`
filter beside it only ever *extends* a `parent` list a block already has —
`parent` is a restriction, so giving one to a block that had none would remove
that block from the inserter everywhere else on the site.

## Deprecations

`src/deprecated.js` and `src/menu-items/deprecated.js` keep the previous `save`
output so menus built before this version load without an invalid-content
warning. If you change `save`, add a deprecation — and reproduce the old markup
exactly, bugs included, because matching is done on the serialised HTML.
